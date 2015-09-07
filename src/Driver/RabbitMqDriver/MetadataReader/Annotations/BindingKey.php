<?php
/**
 * @link https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\Driver\RabbitMqDriver\MetadataReader\Annotations;

/**
 * Class BindingKey
 *
 * @package OldTown\EventBus\Driver\RabbitMqDriver\MetadataReader\Annotations
 *
 * @Annotation
 * @Target("ANNOTATION")
 */
class BindingKey
{
    /**
     * @var string
     */
    public $name;
}
