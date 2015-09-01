<?php
/**
 * @link https://github.com/old-town/event-buss
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBuss\EventBussManager;

use OldTown\EventBuss\Driver\EventBussDriverInterface;

/**
 * Class EventBussManagerFacade
 *
 * @package OldTown\EventBuss\EventBussManagerFacade
 */
class EventBussManagerFacade implements EventBussManagerInterface
{
    /**
     * @var EventBussDriverInterface
     */
    protected $driver;

    /**
     * @param EventBussDriverInterface $driver
     */
    public function __construct(EventBussDriverInterface $driver)
    {
        $this->setDriver($driver);
    }

    /**
     * @return EventBussDriverInterface
     */
    public function getDriver()
    {
        return $this->driver;
    }

    /**
     * @param EventBussDriverInterface $driver
     *
     * @return $this
     */
    public function setDriver(EventBussDriverInterface $driver)
    {
        $this->driver = $driver;

        return $this;
    }
}
