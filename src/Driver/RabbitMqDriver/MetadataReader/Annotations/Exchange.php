<?php
/**
 * @link https://github.com/old-town/event-buss
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\Driver\RabbitMqDriver\MetadataReader\Annotations;

/**
 * Class Exchange
 *
 * @package OldTown\EventBus\Driver\RabbitMqDriver\MetadataReader\Annotations
 *
 * @Annotation
 * @Target("ANNOTATION")
 */
class Exchange
{
    /**
     * @var string
     * @Required
     */
    public $name;

    /**
     * @Enum({"direct", "fanout", "header", "topic"})
     * @Required
     */
    public $type;

    /**
     * @var bool
     */
    public $durable;
}
