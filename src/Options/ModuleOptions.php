<?php
/**
 * @link https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\Options;

use Zend\Stdlib\AbstractOptions;


/**
 * Class ModuleOptions
 * @package ContractModule\Options
 */
class ModuleOptions extends AbstractOptions
{
    /**
     * Описание используемых подключений
     *
     * @var array
     */
    protected $connection;

    /**
     * Конфигурация EventBusManagerFacade
     *
     * @var  array
     */
    protected $EventBusManager;

    /**
     * Конфигурация драйверов реализующих взаимодействие с шиной
     *
     * @var  array
     */
    protected $driver;

    /**
     * @return array
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * @param array $connection
     *
     * @return $this
     */
    public function setConnection(array $connection)
    {
        $this->connection = $connection;

        return $this;
    }

    /**
     * @return array
     */
    public function getEventBusManager()
    {
        return $this->EventBusManager;
    }

    /**
     * @param array $EventBusManager
     *
     * @return $this
     */
    public function setEventBusManager(array $EventBusManager)
    {
        $this->EventBusManager = $EventBusManager;

        return $this;
    }

    /**
     * @return array
     */
    public function getDriver()
    {
        return $this->driver;
    }

    /**
     * @param array $driver
     *
     * @return $this
     */
    public function setDriver(array $driver)
    {
        $this->driver = $driver;

        return $this;
    }
}
