<?php
/**
 * @link https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\EventBusManager;

use OldTown\EventBus\Driver\EventBusDriverInterface;

/**
 * Interface EventBusManagerInterface
 *
 * @package OldTown\EventBus\EventBusManagerFacade
 */
interface EventBusManagerInterface
{
    /**
     * @return EventBusDriverInterface
     */
    public function getDriver();

    /**
     * @param EventBusDriverInterface $driver
     *
     * @return $this
     */
    public function setDriver(EventBusDriverInterface $driver);
}
