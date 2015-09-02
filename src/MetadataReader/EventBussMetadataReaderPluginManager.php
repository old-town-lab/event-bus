<?php
/**
 * @link https://github.com/old-town/event-buss
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBuss\MetadataReader;

use Zend\ServiceManager\AbstractPluginManager;


/**
 * Class EventBussMetadataReaderPluginManager
 *
 * @package OldTown\EventBuss\MetadataReader
 */
class EventBussMetadataReaderPluginManager extends AbstractPluginManager
{
    /**
     * @param mixed $plugin
     *
     * @throws Exception\InvalidEventBussMetadataReaderException
     */
    public function validatePlugin($plugin)
    {
        if (!$plugin instanceof ReaderInterface) {
            $errMsg = sprintf('MetadataReader должен реализовывать %s', ReaderInterface::class);
            throw new Exception\InvalidEventBussMetadataReaderException($errMsg);
        }
    }
}
