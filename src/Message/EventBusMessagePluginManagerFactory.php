<?php
/**
 * @link https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\Message;

use Zend\Mvc\Service\AbstractPluginManagerFactory;


/**
 * Class EventBusMessagePluginManagerFactory
 *
 * @package OldTown\EventBus\Message
 */
class EventBusMessagePluginManagerFactory extends AbstractPluginManagerFactory
{
    const PLUGIN_MANAGER_CLASS = EventBusMessagePluginManager::class;
}
