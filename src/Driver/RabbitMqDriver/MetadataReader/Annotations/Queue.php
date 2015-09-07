<?php
/**
 * @link https://github.com/old-town/event-buss
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\Driver\RabbitMqDriver\MetadataReader\Annotations;

/**
 * Class Queue
 *
 * @package OldTown\EventBus\Driver\RabbitMqDriver\MetadataReader\Annotations
 *
 * @Annotation
 * @Target("ANNOTATION")
 */
class Queue
{
    /**
     * @var string
     *
     * @Required
     */
    public $name;
}
