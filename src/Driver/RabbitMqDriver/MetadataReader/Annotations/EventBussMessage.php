<?php
/**
 * @link https://github.com/old-town/event-buss
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBuss\Driver\RabbitMqDriver\MetadataReader\Annotations;

/**
 * Class EventBussMessage
 *
 * @package OldTown\EventBuss\Driver\RabbitMqDriver\MetadataReader\Annotations
 *
 * @Annotation
 * @Target("CLASS")
 */
class EventBussMessage
{
    /**
     * @var \OldTown\EventBuss\Driver\RabbitMqDriver\MetadataReader\Annotations\Queue
     */
    public $queue;

    /**
     * @var \OldTown\EventBuss\Driver\RabbitMqDriver\MetadataReader\Annotations\Exchange
     */
    public $exchange;

    /**
     * @var array<\OldTown\EventBuss\Driver\RabbitMqDriver\MetadataReader\Annotations\BindingKey>
     */
    public $bindingKeys = [];
}
