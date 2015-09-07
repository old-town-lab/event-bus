<?php
/**
 * @link https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\Driver;

use Zend\ServiceManager\AbstractPluginManager;


/**
 * Class EventBusDriverPluginManager
 *
 * @package OldTown\EventBus\EventBusManagerFacade
 */
class EventBusDriverPluginManager extends AbstractPluginManager
{
    /**
     * @param mixed $plugin
     *
     * @throws Exception\InvalidEventBusDriverException
     */
    public function validatePlugin($plugin)
    {
        if (!$plugin instanceof EventBusDriverInterface) {
            $errMsg = sprintf('EventBusDriver должен реализовывать %s', EventBusDriverInterface::class);
            throw new Exception\InvalidEventBusDriverException($errMsg);
        }
    }
}
