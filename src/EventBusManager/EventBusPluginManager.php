<?php
/**
 * @link https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\EventBusManager;

use Zend\ServiceManager\AbstractPluginManager;


/**
 * Class EventBusManagerFacade
 *
 * @package OldTown\EventBus\EventBusManagerFacade
 */
class EventBusPluginManager extends AbstractPluginManager
{
    /**
     * @param mixed $plugin
     *
     * @throws Exception\InvalidEventBusManagerException
     */
    public function validatePlugin($plugin)
    {
        if (!$plugin instanceof EventBusManagerInterface) {
            $errMsg = sprintf('EventBusManager должен реализовывать %s', EventBusManagerInterface::class);
            throw new Exception\InvalidEventBusManagerException($errMsg);
        }
    }
}
