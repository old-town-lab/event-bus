<?php
/**
 * @link https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\MetadataReader;

use Zend\Mvc\Service\AbstractPluginManagerFactory;


/**
 * Class EventBusMetadataReaderPluginManagerFactory
 *
 * @package OldTown\EventBus\MetadataReader
 */
class EventBusMetadataReaderPluginManagerFactory extends AbstractPluginManagerFactory
{
    const PLUGIN_MANAGER_CLASS = EventBusMetadataReaderPluginManager::class;
}
