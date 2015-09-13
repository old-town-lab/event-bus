<?php
/**
 * @link https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\Driver\RabbitMqDriver\Adapter;

use OldTown\EventBus\Driver\RabbitMqDriver\MetadataReader\MetadataInterface;
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

    /**
     * @param MetadataInterface $metadata
     * @param                   $callback
     *
     */
    public function attach(MetadataInterface $metadata, callable $callback);

    /**
     * На основе данных пришедших из очереди, извлекат тип Serializer, которым эти данные упкаованы
     *
     * @param array $rawData
     *
     * @return string
     */
    public function extractSerializerName(array $rawData = []);

    /**
     * На основе данных пришедших из очереди, извлекат сериализованные данные
     *
     * @param array $rawData
     *
     * @return string
     */
    public function extractSerializedData(array $rawData = []);
}
