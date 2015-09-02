<?php
/**
 * @link https://github.com/old-town/event-buss
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBuss\Driver\RabbitMqDriver\MetadataReader\Annotations;

/**
 * Class Queue
 *
 * @package OldTown\EventBuss\Driver\RabbitMqDriver\MetadataReader\Annotations
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
