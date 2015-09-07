<?php
/**
 * @link https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\Driver;

/**
 * Interface EventBusDriverProviderInterface
 *
 * @package OldTown\EventBus\Driver
 */
interface EventBusDriverProviderInterface
{
    /**
     * @return array
     */
    public function getEventBusDriverConfig();
}
