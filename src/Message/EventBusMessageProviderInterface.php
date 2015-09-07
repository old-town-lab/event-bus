<?php
/**
 * @link https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\Message;

/**
 * Interface EventBusMessageProviderInterface
 *
 * @package OldTown\EventBus\Message
 */
interface EventBusMessageProviderInterface
{
    /**
     * @return array
     */
    public function getEventBusMessageConfig();
}
