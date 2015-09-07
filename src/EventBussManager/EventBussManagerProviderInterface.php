<?php
/**
 * @link https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\EventBussManager;

/**
 * Class EventBussPluginManager
 *
 * @package OldTown\EventBus\EventBussManager
 */
interface EventBussManagerProviderInterface
{
    /**
     * @return array
     */
    public function getEventBussManagerConfig();
}
