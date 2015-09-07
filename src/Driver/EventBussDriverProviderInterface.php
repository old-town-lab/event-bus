<?php
/**
 * @link https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\Driver;

/**
 * Interface EventBussDriverProviderInterface
 *
 * @package OldTown\EventBus\Driver
 */
interface EventBussDriverProviderInterface
{
    /**
     * @return array
     */
    public function getEventBussDriverConfig();
}
