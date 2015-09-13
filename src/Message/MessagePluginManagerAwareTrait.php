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
trait MessagePluginManagerAwareTrait
{
    /**
     * @var EventBusMessagePluginManager
     */
    protected $messagePluginManager;

    /**
     * @param EventBusMessagePluginManager $messagePluginManager
     *
     * @return $this
     */
    public function setMessagePluginManager(EventBusMessagePluginManager $messagePluginManager)
    {
        $this->messagePluginManager = $messagePluginManager;

        return $this;
    }

    /**
     *
     * @return EventBusMessagePluginManager
     */
    public function getMessagePluginManager()
    {
        return $this->messagePluginManager;
    }
}
