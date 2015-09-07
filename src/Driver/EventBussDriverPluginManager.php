<?php
/**
 * @link https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\Driver;

use Zend\ServiceManager\AbstractPluginManager;


/**
 * Class EventBussDriverPluginManager
 *
 * @package OldTown\EventBus\EventBussManagerFacade
 */
class EventBussDriverPluginManager extends AbstractPluginManager
{
    /**
     * @param mixed $plugin
     *
     * @throws Exception\InvalidEventBussDriverException
     */
    public function validatePlugin($plugin)
    {
        if (!$plugin instanceof EventBussDriverInterface) {
            $errMsg = sprintf('EventBussDriver должен реализовывать %s', EventBussDriverInterface::class);
            throw new Exception\InvalidEventBussDriverException($errMsg);
        }
    }
}
