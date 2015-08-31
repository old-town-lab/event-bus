<?php
/**
 * @link https://github.com/old-town/event-buss
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBuss\EventBussManager;

/**
 * Class EventBussPluginManager
 *
 * @package OldTown\EventBuss\EventBussManager
 */
interface EventBussManagerProviderInterface
{
    /**
     * @return array
     */
    public function getEventBussManagerConfig();
}
