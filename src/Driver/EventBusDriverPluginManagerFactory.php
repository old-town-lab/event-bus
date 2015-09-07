<?php
/**
 * @link https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\Driver;

use Zend\Mvc\Service\AbstractPluginManagerFactory;


/**
 * Class EventBusDriverPluginManagerFactory
 *
 * @package OldTown\EventBus\Driver
 */
class EventBusDriverPluginManagerFactory extends AbstractPluginManagerFactory
{
    const PLUGIN_MANAGER_CLASS = EventBusDriverPluginManager::class;
}
