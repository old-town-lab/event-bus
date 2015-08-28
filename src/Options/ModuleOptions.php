<?php
/**
 * @link https://github.com/old-town/event-buss
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBuss\Options;


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
     * Конфигурация EventBussManager
     *
     * @var  array
     */
    protected $eventBussManager;

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
    public function getEventBussManager()
    {
        return $this->eventBussManager;
    }

    /**
     * @param array $eventBussManager
     *
     * @return $this
     */
    public function setEventBussManager(array $eventBussManager)
    {
        $this->eventBussManager = $eventBussManager;

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