<?php
/**
 * @link https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\EventBussManager;

use OldTown\EventBus\Driver\EventBussDriverInterface;

/**
 * Interface EventBussManagerInterface
 *
 * @package OldTown\EventBus\EventBussManagerFacade
 */
interface EventBussManagerInterface
{
    /**
     * @return EventBussDriverInterface
     */
    public function getDriver();

    /**
     * @param EventBussDriverInterface $driver
     *
     * @return $this
     */
    public function setDriver(EventBussDriverInterface $driver);
}
