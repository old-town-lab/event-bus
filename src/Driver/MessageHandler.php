<?php
/**
 * @link https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\Driver;

use OldTown\EventBus\Message\EventBusMessagePluginManager;
use OldTown\EventBus\Message\MessageInterface;

/**
 * Class MessageConsumer
 *
 * @package OldTown\EventBus\Driver
 */
class MessageHandler
{
    /**
     * @var callable
     */
    protected $callback;

    /**
     * @var EventBusDriverInterface
     */
    protected $driver;

    /**
     * @var EventBusMessagePluginManager
     */
    protected $messagePluginManager;

    /**
     * Имя класса/сервиса - сообщения
     *
     * @var string
     */
    protected $messageName;

    /**
     * @param                              $messageName
     * @param callable                     $callback
     * @param EventBusDriverInterface      $driver
     * @param EventBusMessagePluginManager $messagePluginManager
     */
    public function __construct($messageName, callable $callback, EventBusDriverInterface $driver, EventBusMessagePluginManager $messagePluginManager)
    {
        $this->setCallback($callback);
        $this->setDriver($driver);
        $this->setMessageName($messageName);
        $this->setMessagePluginManager($messagePluginManager);
    }

    /**
     * @return string
     */
    public function getMessageName()
    {
        return $this->messageName;
    }

    /**
     * @param string $messageName
     *
     * @return $this
     */
    public function setMessageName($messageName)
    {
        $this->messageName = (string)$messageName;

        return $this;
    }


    /**
     * @return EventBusMessagePluginManager
     */
    public function getMessagePluginManager()
    {
        return $this->messagePluginManager;
    }

    /**
     * @param EventBusMessagePluginManager $messagePluginManager
     *
     * @return $this
     */
    public function setMessagePluginManager($messagePluginManager)
    {
        $this->messagePluginManager = $messagePluginManager;

        return $this;
    }

    /**
     * @return EventBusDriverInterface
     */
    public function getDriver()
    {
        return $this->driver;
    }

    /**
     * @param EventBusDriverInterface $driver
     *
     * @return $this
     */
    public function setDriver(EventBusDriverInterface $driver)
    {
        $this->driver = $driver;

        return $this;
    }


    /**
     * @return callable
     */
    public function getCallback()
    {
        return $this->callback;
    }

    /**
     * @param callable $callback
     *
     * @return $this
     */
    public function setCallback(callable $callback)
    {
        $this->callback = $callback;

        return $this;
    }


    /**
     * Делегирование обработки сообщения зарегестрированному callback'у
     *
     *
     * @return boolean
     *
     * @throws \OldTown\EventBus\Driver\Exception\ErrorExtractSerializerNameException
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     * @throws \Zend\ServiceManager\Exception\ServiceNotCreatedException
     * @throws \Zend\ServiceManager\Exception\RuntimeException
     *
     */
    public function __invoke()
    {
        $driver = $this->getDriver();
        if (!$driver instanceof ExtractorDataFromEventBusInterface) {
            $errMsg = sprintf('Драйвер должен реализовывать интерфейс %s', ExtractorDataFromEventBusInterface::class);
            throw new Exception\ErrorExtractSerializerNameException($errMsg);
        }

        $args = func_get_args();

        $serializerName = $driver->extractSerializerName($args);
        $serializedData = $driver->extractSerializedData($args);

        $messageName = $this->getMessageName();
        /** @var MessageInterface $message */
        $message = $this->getMessagePluginManager()->get($messageName);

        $message->setSerializerName($serializerName);
        $message->setContent($serializedData);

        $flag = call_user_func($this->getCallback(), $message);

        return $flag;
    }
}
