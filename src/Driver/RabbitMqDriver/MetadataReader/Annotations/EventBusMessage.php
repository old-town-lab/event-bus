<?php
/**
 * @link https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\Driver\RabbitMqDriver\MetadataReader\Annotations;

/**
 * Class EventBusMessage
 *
 * @package OldTown\EventBus\Driver\RabbitMqDriver\MetadataReader\Annotations
 *
 * @Annotation
 * @Target("CLASS")
 */
class EventBusMessage
{
    /**
     * @var \OldTown\EventBus\Driver\RabbitMqDriver\MetadataReader\Annotations\Queue
     */
    public $queue;

    /**
     * @var \OldTown\EventBus\Driver\RabbitMqDriver\MetadataReader\Annotations\Exchange
     */
    public $exchange;

    /**
     * @var array<\OldTown\EventBus\Driver\RabbitMqDriver\MetadataReader\Annotations\BindingKey>
     */
    public $bindingKeys = [];
}
