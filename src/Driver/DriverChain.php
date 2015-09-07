<?php
/**
 * @link https://github.com/old-town/event-buss
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
     * @var EventBussDriverInterface[]|SplObjectStorage
     */
    protected $drivers;

    /**
     * @var EventBussDriverPluginManager
     */
    protected $eventBussDriverPluginManager;

    /**
     * @param array $options
     * @param EventBussDriverPluginManager $eventBussDriverPluginManager
     *
     * @throws \Zend\Stdlib\Exception\InvalidArgumentException
     * @throws \OldTown\EventBus\Driver\Exception\InvalidArgumentException
     */
    public function __construct($options = null, EventBussDriverPluginManager $eventBussDriverPluginManager)
    {
        $this->drivers = new \SplObjectStorage();
        $this->setEventBussDriverPluginManager($eventBussDriverPluginManager);
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
     * @param EventBussDriverInterface $driver
     *
     * @return $this
     */
    public function addDriver($driver)
    {
        $this->drivers->attach($driver);

        return $this;
    }

    /**
     * @return EventBussDriverInterface[]|SplObjectStorage
     */
    public function getDrivers()
    {
        return $this->drivers;
    }

    /**
     * @return EventBussDriverPluginManager
     */
    public function getEventBussDriverPluginManager()
    {
        return $this->eventBussDriverPluginManager;
    }

    /**
     * @param EventBussDriverPluginManager $eventBussDriverPluginManager
     *
     * @return $this
     */
    public function setEventBussDriverPluginManager(EventBussDriverPluginManager $eventBussDriverPluginManager)
    {
        $this->eventBussDriverPluginManager = $eventBussDriverPluginManager;

        return $this;
    }


    /**
     * @param array $options
     *
     * @return $this
     *
     * @throws \OldTown\EventBus\Driver\Exception\ErrorCreateEventBussDriverException
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
     * @throws \OldTown\EventBus\Driver\Exception\ErrorCreateEventBussDriverException
     */
    protected function buildDriversFromConfig(array $config = [])
    {
        foreach ($config as $key => $driverConfigArray) {
            $driverConfig = new DriverConfig($driverConfigArray);

            try {
                $pluginName = $driverConfig->getPluginName();
                $pluginConfig = $driverConfig->getPluginConfig();
                /** @var EventBussDriverInterface $driver */
                $driver = $this->getEventBussDriverPluginManager()->get($pluginName, $pluginConfig);

                $this->addDriver($driver);
            } catch (\Exception $e) {
                throw new Exception\ErrorCreateEventBussDriverException($e->getMessage(), $e->getCode(), $e);
            }
        }
    }


    /**
     * Инициализация шины
     *
     * @return void
     */
    public function initEventBuss()
    {
        $drivers = $this->getDrivers();
        foreach ($drivers as $driver) {
            $driver->initEventBuss();
        }
    }
}
