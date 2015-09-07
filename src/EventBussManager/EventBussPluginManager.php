<?php
/**
 * @link https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\EventBussManager;

use Zend\ServiceManager\AbstractPluginManager;


/**
 * Class EventBussManagerFacade
 *
 * @package OldTown\EventBus\EventBussManagerFacade
 */
class EventBussPluginManager extends AbstractPluginManager
{
    /**
     * @param mixed $plugin
     *
     * @throws Exception\InvalidEventBussManagerException
     */
    public function validatePlugin($plugin)
    {
        if (!$plugin instanceof EventBussManagerInterface) {
            $errMsg = sprintf('EventBussManager должен реализовывать %s', EventBussManagerInterface::class);
            throw new Exception\InvalidEventBussManagerException($errMsg);
        }
    }
}
