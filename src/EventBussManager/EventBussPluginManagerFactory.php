<?php
/**
 * @link https://github.com/old-town/event-buss
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBuss\EventBussManager;
use Zend\Mvc\Service\AbstractPluginManagerFactory;


/**
 * Class EventBussPluginManagerFactory
 *
 * @package OldTown\EventBuss\EventBussManager
 */
class EventBussPluginManagerFactory extends AbstractPluginManagerFactory
{
    const PLUGIN_MANAGER_CLASS = EventBussPluginManager::class;
}
