<?php
/**
 * @link https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\EventBusManager;

/**
 * Class EventBusPluginManager
 *
 * @package OldTown\EventBus\EventBusManager
 */
interface EventBusManagerProviderInterface
{
    /**
     * @return array
     */
    public function getEventBusManagerConfig();
}
