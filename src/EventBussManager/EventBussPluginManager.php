<?php
/**
 * @link https://github.com/old-town/event-buss
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBuss\EventBussManager;

use Zend\ServiceManager\AbstractPluginManager;


/**
 * Class EventBussManager
 *
 * @package OldTown\EventBuss\EventBussManager
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
