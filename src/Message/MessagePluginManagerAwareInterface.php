<?php
/**
 * @link https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\Message;

/**
 * Interface MessagePluginManagerAwareInterface
 *
 * @package OldTown\EventBus\Message
 */
interface MessagePluginManagerAwareInterface
{
    /**
     * @param EventBusMessagePluginManager $messagePluginManager
     *
     * @return $this
     */
    public function setMessagePluginManager(EventBusMessagePluginManager $messagePluginManager);

    /**
     *
     * @return EventBusMessagePluginManager
     */
    public function getMessagePluginManager();
}
