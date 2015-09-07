<?php
/**
 * @link https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\MetadataReader;

use Zend\Mvc\Service\AbstractPluginManagerFactory;


/**
 * Class EventBussMetadataReaderPluginManagerFactory
 *
 * @package OldTown\EventBus\MetadataReader
 */
class EventBussMetadataReaderPluginManagerFactory extends AbstractPluginManagerFactory
{
    const PLUGIN_MANAGER_CLASS = EventBussMetadataReaderPluginManager::class;
}
