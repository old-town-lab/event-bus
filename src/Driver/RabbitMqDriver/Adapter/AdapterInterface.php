<?php
/**
 * @link https://github.com/old-town/event-buss
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBuss\Driver\RabbitMqDriver\Adapter;

use OldTown\EventBuss\MetadataReader\MetadataInterface;
use OldTown\EventBuss\Message\MessageInterface;

/**
 * Interface AdapterInterface
 *
 * @package OldTown\EventBuss\Driver\RabbitMqDriver\Adapter
 */
interface AdapterInterface
{
    /**
     * Инициализация шины
     *
     * @param MetadataInterface[] $metadata
     */
    public function initEventBuss(array $metadata = []);


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
