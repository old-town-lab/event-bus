<?php
/**
 * @link https://github.com/old-town/event-buss
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBuss\Driver;

use Zend\EventManager\EventInterface;
use Zend\EventManager\ResponseCollection;
use SplObjectStorage;

/**
 * Class DriverChain
 *
 * @package OldTown\EventBuss\Driver
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
     * @throws \OldTown\EventBuss\Driver\Exception\InvalidArgumentException
     */
    public function __construct($options = null, EventBussDriverPluginManager $eventBussDriverPluginManager)
    {
        $this->drivers = new \SplObjectStorage();
        $this->setEventBussDriverPluginManager($eventBussDriverPluginManager);
        parent::__construct($options);
    }


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
     * @throws \OldTown\EventBuss\Driver\Exception\ErrorCreateEventBussDriverException
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
     * @throws \OldTown\EventBuss\Driver\Exception\ErrorCreateEventBussDriverException
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
