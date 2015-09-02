<?php
/**
 * @link https://github.com/old-town/event-buss
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBuss\Driver;

use OldTown\EventBuss\Driver\RabbitMqDriver\Adapter\AmqpPhpExtension;
use Zend\EventManager\EventInterface;
use Zend\EventManager\ResponseCollection;
use OldTown\EventBuss\Driver\RabbitMqDriver\Adapter\AdapterInterface;
use OldTown\EventBuss\Driver\RabbitMqDriver\MetadataReader\AnnotationReader;


/**
 * Class RabbitMqDriver
 *
 * @package OldTown\EventBuss\Driver
 */
class RabbitMqDriver extends AbstractDriver implements ConnectionDriverInterface, MetadataReaderInterface
{
    use ConnectionDriverTrait, MetadataReaderTrait;

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
     * Trigger an event
     *
     * Should allow handling the following scenarios:
     * - Passing Event object only
     * - Passing event name and Event object only
     * - Passing event name, target, and Event object
     * - Passing event name, target, and array|ArrayAccess of arguments
     * - Passing event name, target, array|ArrayAccess of arguments, and callback
     *
     * @param  string|EventInterface $event
     * @param  object|string $target
     * @param  array|object $argv
     * @param  null|callable $callback
     * @return ResponseCollection
     */
    public function trigger($event, $target = null, $argv = [], $callback = null)
    {
    }

    /**
     * Возвращает имя используемого адаптера
     *
     * @return string
     *
     * @throws \OldTown\EventBuss\Driver\Exception\InvalidAdapterNameException
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
     * @throws \OldTown\EventBuss\Driver\Exception\InvalidEventBussDriverConfigException
     * @throws \OldTown\EventBuss\Driver\Exception\InvalidAdapterNameException
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
     * @throws \OldTown\EventBuss\Driver\Exception\InvalidEventBussDriverConfigException
     * @throws \OldTown\EventBuss\Driver\Exception\InvalidAdapterNameException
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     * @throws \Zend\ServiceManager\Exception\ServiceNotCreatedException
     * @throws \Zend\ServiceManager\Exception\RuntimeException
     * @throws \OldTown\EventBuss\Driver\Exception\InvalidMetadataReaderNameException
     */
    public function initEventBuss()
    {
        $paths = $this->getPaths();
        $messages = $this->getMetadataReader()->getAllClassNames();
    }
}
