<?php
/**
 * @link https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\MetadataReader;

use Zend\ServiceManager\AbstractPluginManager;


/**
 * Class EventBusMetadataReaderPluginManager
 *
 * @package OldTown\EventBus\MetadataReader
 */
class EventBusMetadataReaderPluginManager extends AbstractPluginManager
{
    /**
     * @param mixed $plugin
     *
     * @throws Exception\InvalidEventBusMetadataReaderException
     */
    public function validatePlugin($plugin)
    {
        if (!$plugin instanceof ReaderInterface) {
            $errMsg = sprintf('MetadataReader должен реализовывать %s', ReaderInterface::class);
            throw new Exception\InvalidEventBusMetadataReaderException($errMsg);
        }
    }
}
