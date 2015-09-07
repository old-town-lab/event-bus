<?php
/**
 * @link https://github.com/old-town/event-buss
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\Driver;

use Zend\Mvc\Service\AbstractPluginManagerFactory;


/**
 * Class EventBussDriverPluginManagerFactory
 *
 * @package OldTown\EventBus\Driver
 */
class EventBussDriverPluginManagerFactory extends AbstractPluginManagerFactory
{
    const PLUGIN_MANAGER_CLASS = EventBussDriverPluginManager::class;
}
