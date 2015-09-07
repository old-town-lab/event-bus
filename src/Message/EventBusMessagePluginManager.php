<?php
/**
 * @link https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\Message;

use Zend\ServiceManager\AbstractPluginManager;


/**
 * Class EventBusMetadataReaderPluginManager
 *
 * @package OldTown\EventBus\MetadataReader
 */
class EventBusMessagePluginManager extends AbstractPluginManager
{
    /**
     * @param mixed $plugin
     *
     * @throws Exception\InvalidEventBusMessageException
     */
    public function validatePlugin($plugin)
    {
        if (!$plugin instanceof MessageInterface) {
            $errMsg = sprintf('Класс сообщения ддолжен реализовывать %s', MessageInterface::class);
            throw new Exception\InvalidEventBusMessageException($errMsg);
        }
    }
}
