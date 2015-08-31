<?php
/**
 * @link https://github.com/old-town/event-buss
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBuss\EventBussManager;

use OldTown\EventBuss\Driver\EventBussDriverInterface;

/**
 * Interface EventBussManagerInterface
 *
 * @package OldTown\EventBuss\EventBussManager
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
