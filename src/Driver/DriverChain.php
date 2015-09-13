<?php
/**
 * @link https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\Driver;

use OldTown\EventBus\Message\MessageInterface;
use SplObjectStorage;

/**
 * Class DriverChain
 *
 * @package OldTown\EventBus\Driver
 */
class DriverChain extends  AbstractDriver
{
    /**
     * @var EventBusDriverInterface[]|SplObjectStorage
     */
    protected $drivers;

    /**
     * @var EventBusDriverPluginManager
     */
    protected $eventBusDriverPluginManager;

    /**
     * @param array $options
     * @param EventBusDriverPluginManager $eventBusDriverPluginManager
     *
     * @throws \Zend\Stdlib\Exception\InvalidArgumentException
     * @throws \OldTown\EventBus\Driver\Exception\InvalidArgumentException
     */
    public function __construct($options = null, EventBusDriverPluginManager $eventBusDriverPluginManager)
    {
        $this->drivers = new \SplObjectStorage();
        $this->setEventBusDriverPluginManager($eventBusDriverPluginManager);
        parent::__construct($options);
    }

    /**
     * @param $eventName
     * @param MessageInterface $message
     */
    public function trigger($eventName, MessageInterface $message)
    {
    }

    /**
     * @param EventBusDriverInterface $driver
     *
     * @return $this
     */
    public function addDriver($driver)
    {
        $this->drivers->attach($driver);

        return $this;
    }

    /**
     * @return EventBusDriverInterface[]|SplObjectStorage
     */
    public function getDrivers()
    {
        return $this->drivers;
    }

    /**
     * @return EventBusDriverPluginManager
     */
    public function getEventBusDriverPluginManager()
    {
        return $this->eventBusDriverPluginManager;
    }

    /**
     * @param EventBusDriverPluginManager $eventBusDriverPluginManager
     *
     * @return $this
     */
    public function setEventBusDriverPluginManager(EventBusDriverPluginManager $eventBusDriverPluginManager)
    {
        $this->eventBusDriverPluginManager = $eventBusDriverPluginManager;

        return $this;
    }


    /**
     * @param array $options
     *
     * @return $this
     *
     * @throws \OldTown\EventBus\Driver\Exception\ErrorCreateEventBusDriverException
     * @throws \OldTown\EventBus\Driver\Exception\InvalidEventBusDriverConfigException
     * @throws \OldTown\EventBus\Driver\Exception\InvalidArgumentException
     */
    public function setOptions(array $options = [])
    {
        if (array_key_exists(DriverConfig::DRIVERS, $options) && is_array($options[DriverConfig::DRIVERS])) {
            $this->buildDriversFromConfig($options[DriverConfig::DRIVERS]);
        }

        return $this;
    }

    /**
     * @param array $config
     *
     * @return $this
     *
     * @throws \OldTown\EventBus\Driver\Exception\ErrorCreateEventBusDriverException
     * @throws \OldTown\EventBus\Driver\Exception\InvalidEventBusDriverConfigException
     * @throws \OldTown\EventBus\Driver\Exception\InvalidArgumentException
     */
    protected function buildDriversFromConfig(array $config = [])
    {
        foreach ($config as $key => $driverConfigArray) {
            $driverConfig = new DriverConfig($driverConfigArray);

            try {
                $pluginName = $driverConfig->getPluginName();
                $pluginConfig = $driverConfig->getPluginConfig();
                /** @var EventBusDriverInterface $driver */
                $driver = $this->getEventBusDriverPluginManager()->get($pluginName, $pluginConfig);

                $this->addDriver($driver);
            } catch (\Exception $e) {
                throw new Exception\ErrorCreateEventBusDriverException($e->getMessage(), $e->getCode(), $e);
            }
        }
    }


    /**
     * Инициализация шины
     *
     * @return void
     */
    public function initEventBus()
    {
        $drivers = $this->getDrivers();
        foreach ($drivers as $driver) {
            $driver->initEventBus();
        }
    }


    /**
     * Подписывается на прием сообщений
     *
     * @param string   $messageName
     * @param callable $callback
     */
    public function attach($messageName, callable $callback)
    {
    }
}
