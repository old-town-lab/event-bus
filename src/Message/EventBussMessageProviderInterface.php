<?php
/**
 * @link https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\Message;

/**
 * Interface EventBussMessageProviderInterface
 *
 * @package OldTown\EventBus\Message
 */
interface EventBussMessageProviderInterface
{
    /**
     * @return array
     */
    public function getEventBussMessageConfig();
}
