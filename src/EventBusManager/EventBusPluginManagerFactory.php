<?php
/**
 * @link https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\EventBusManager;

use Zend\Mvc\Service\AbstractPluginManagerFactory;


/**
 * Class EventBusPluginManagerFactory
 *
 * @package OldTown\EventBus\EventBusManager
 */
class EventBusPluginManagerFactory extends AbstractPluginManagerFactory
{
    const PLUGIN_MANAGER_CLASS = EventBusPluginManager::class;
}
