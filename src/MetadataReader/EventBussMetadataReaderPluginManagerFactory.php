<?php
/**
 * @link https://github.com/old-town/event-buss
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBuss\MetadataReader;

use Zend\Mvc\Service\AbstractPluginManagerFactory;


/**
 * Class EventBussMetadataReaderPluginManagerFactory
 *
 * @package OldTown\EventBuss\MetadataReader
 */
class EventBussMetadataReaderPluginManagerFactory extends AbstractPluginManagerFactory
{
    const PLUGIN_MANAGER_CLASS = EventBussMetadataReaderPluginManager::class;
}