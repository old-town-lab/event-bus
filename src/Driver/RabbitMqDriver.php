<?php
/**
 * @link https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\Driver;

use OldTown\EventBus\Driver\RabbitMqDriver\Adapter\AmqpPhpExtension;
use OldTown\EventBus\Driver\RabbitMqDriver\MetadataReader\MetadataInterface;
use OldTown\EventBus\Message\MessageInterface;
use OldTown\EventBus\Driver\RabbitMqDriver\Adapter\AdapterInterface;
use OldTown\EventBus\Driver\RabbitMqDriver\MetadataReader\AnnotationReader;
use OldTown\EventBus\Message\MessagePluginManagerAwareInterface;
use OldTown\EventBus\Message\MessagePluginManagerAwareTrait;


/**
 * Class RabbitMqDriver
 *
 * @package OldTown\EventBus\Driver
 */
class RabbitMqDriver
    extends AbstractDriver
    implements
        ConnectionDriverInterface,
        MetadataReaderInterface,
        ExtractorDataFromEventBusInterface,
        MessagePluginManagerAwareInterface
{
    use ConnectionDriverTrait, MetadataReaderTrait, MessagePluginManagerAwareTrait;

    /**
     * Имя секции в extraOptions содержащее имя драйвера
     *
     * @var string
     */
    const ADAPTER = 'adapter';

    /**
     * Имя адаптера по умолчанию
     *
     * @var string
     */
    protected static $defaultAdapterName = AmqpPhpExtension::class;

    /**
     * Имя используемого адаптера
     *
     * @var string
     */
    protected $adapterName;

    /**
     * Адаптер для работы с RabbitMq
     *
     * @var AdapterInterface
     */
    protected $adapter;


    /**
     * Должно реализовываться к конкретном классе
     *
     * @var string
     */
    protected $defaultMetadataReaderName = AnnotationReader::class;


    /**
     * Возвращает имя используемого адаптера
     *
     * @return string
     *
     * @throws \OldTown\EventBus\Driver\Exception\InvalidAdapterNameException
     */
    public function getAdapterName()
    {
        if ($this->adapterName) {
            return $this->adapterName;
        }

        $extraOptions = $this->getExtraOptions();

        $adapterName = static::$defaultAdapterName;
        if (array_key_exists(static::ADAPTER, $extraOptions)) {
            $adapterName = $extraOptions[static::ADAPTER];
            if (!class_exists($adapterName)) {
                $errMsg = sprintf('Отсутствует класс адаптера %s', $adapterName);
                throw new Exception\InvalidAdapterNameException($errMsg);
            }
            if (!array_key_exists(AdapterInterface::class, class_implements($adapterName))) {
                $errMsg = sprintf('Адаптер должен реализовывать %s', AdapterInterface::class);
                throw new Exception\InvalidAdapterNameException($errMsg);
            }
        }

        $this->adapterName = $adapterName;
        return $this->adapterName;
    }

    /**
     * Возвращает адаптер для работы с сервером очередей
     *
     * @return AdapterInterface
     *
     * @throws \OldTown\EventBus\Driver\Exception\InvalidEventBusDriverConfigException
     * @throws \OldTown\EventBus\Driver\Exception\InvalidAdapterNameException
     */
    public function getAdapter()
    {
        if ($this->adapter) {
            return $this->adapter;
        }

        $connections = $this->getConnectionConfig();
        $adapterName = $this->getAdapterName();

        $adapter = new $adapterName($connections);

        $this->adapter = $adapter;

        return $this->adapter;
    }


    /**
     * Инициализация шины
     *
     * @return void
     *
     * @throws \OldTown\EventBus\Driver\Exception\InvalidEventBusDriverConfigException
     * @throws \OldTown\EventBus\Driver\Exception\InvalidAdapterNameException
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     * @throws \Zend\ServiceManager\Exception\ServiceNotCreatedException
     * @throws \Zend\ServiceManager\Exception\RuntimeException
     * @throws \OldTown\EventBus\Driver\Exception\InvalidMetadataReaderNameException
     * @throws \OldTown\EventBus\MetadataReader\Exception\InvalidPathException
     */
    public function initEventBus()
    {
        $reader = $this->getMetadataReader();
        $allClassNames = $reader->getAllClassNames();

        $metadataStorage = [];
        foreach ($allClassNames as $classNames) {
            /** @var MetadataInterface $metadata */
            $metadata = $reader->loadMetadataForClass($classNames);
            $metadataStorage[$classNames] = $metadata;
        }

        $this->getAdapter()->initEventBus($metadataStorage);
    }

    /**
     * @param $eventName
     * @param MessageInterface $message
     *
     * @throws \OldTown\EventBus\Driver\Exception\InvalidAdapterNameException
     * @throws \OldTown\EventBus\Driver\Exception\InvalidEventBusDriverConfigException
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     * @throws \Zend\ServiceManager\Exception\ServiceNotCreatedException
     * @throws \Zend\ServiceManager\Exception\RuntimeException
     * @throws \OldTown\EventBus\Driver\Exception\InvalidMetadataReaderNameException
     */
    public function trigger($eventName, MessageInterface $message)
    {
        $messageClass = get_class($message);
        /** @var MetadataInterface $metadata */
        $metadata = $this->getMetadataReader()->loadMetadataForClass($messageClass);

        $this->getAdapter()->trigger($eventName, $message, $metadata);
    }

    /**
     * Подписывается на прием сообщений
     *
     * @param          $messageName
     * @param callable $callback
     *
     * @throws \OldTown\EventBus\Driver\Exception\InvalidEventBusDriverConfigException
     * @throws \OldTown\EventBus\Driver\Exception\InvalidAdapterNameException
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     * @throws \Zend\ServiceManager\Exception\ServiceNotCreatedException
     * @throws \Zend\ServiceManager\Exception\RuntimeException
     * @throws \OldTown\EventBus\Driver\Exception\InvalidMetadataReaderNameException
     */
    public function attach($messageName, callable $callback)
    {


        /** @var MetadataInterface $metadata */
        $metadata = $this->getMetadataReader()->loadMetadataForClass($messageName);

        $messagePluginManager = $this->getMessagePluginManager();
        $handler = new MessageHandler($messageName, $callback, $this, $messagePluginManager);
        $this->getAdapter()->attach($metadata, $handler);
    }

    /**
     * На основе данных пришедших из очереди, извлекат тип Serializer, которым эти данные упкаованы
     *
     * @param array $rawData
     *
     * @return string
     *
     * @throws \OldTown\EventBus\Driver\Exception\InvalidEventBusDriverConfigException
     * @throws \OldTown\EventBus\Driver\Exception\InvalidAdapterNameException
     */
    public function extractSerializerName(array $rawData = [])
    {
        $serializerName = $this->getAdapter()->extractSerializerName($rawData);

        return $serializerName;
    }

    /**
     * На основе данных пришедших из очереди, извлекат сериализованные данные
     *
     * @param array $rawData
     *
     * @return string
     *
     * @throws \OldTown\EventBus\Driver\Exception\InvalidEventBusDriverConfigException
     * @throws \OldTown\EventBus\Driver\Exception\InvalidAdapterNameException
     */
    public function extractSerializedData(array $rawData = [])
    {
        $serializedData = $this->getAdapter()->extractSerializedData($rawData);

        return $serializedData;
    }
}
