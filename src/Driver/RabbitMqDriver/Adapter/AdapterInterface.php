<?php
/**
 * @link https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\Driver\RabbitMqDriver\Adapter;

use OldTown\EventBus\MetadataReader\MetadataInterface;
use OldTown\EventBus\Message\MessageInterface;

/**
 * Interface AdapterInterface
 *
 * @package OldTown\EventBus\Driver\RabbitMqDriver\Adapter
 */
interface AdapterInterface
{
    /**
     * Инициализация шины
     *
     * @param MetadataInterface[] $metadata
     */
    public function initEventBus(array $metadata = []);


    /**
     * Настройки соеденения
     *
     * @return array
     */
    public function getConnectionConfig();


    /**
     * @param $eventName
     * @param MessageInterface $message
     * @param MetadataInterface $metadata
     * @return
     */
    public function trigger($eventName, MessageInterface $message, MetadataInterface $metadata);
}
