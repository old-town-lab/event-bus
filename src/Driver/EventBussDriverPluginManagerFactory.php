<?php
/**
 * @link https://github.com/old-town/event-buss
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBuss\Driver;

use Zend\Mvc\Service\AbstractPluginManagerFactory;


/**
 * Class EventBussDriverPluginManagerFactory
 *
 * @package OldTown\EventBuss\Driver
 */
class EventBussDriverPluginManagerFactory extends AbstractPluginManagerFactory
{
    const PLUGIN_MANAGER_CLASS = EventBussDriverPluginManager::class;
}
