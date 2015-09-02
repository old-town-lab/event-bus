<?php
/**
 * @link https://github.com/old-town/event-buss
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBuss\Driver\RabbitMqDriver\MetadataReader;

use OldTown\EventBuss\MetadataReader\AbstractAnnotationReader;

/**
 * Class AnnotationReader
 *
 * @package OldTown\EventBuss\Driver\RabbitMqDriver\MetadataReader
 */
class AnnotationReader extends AbstractAnnotationReader
{
    /**
     * @var array
     */
    protected $messageAnnotationClasses = [
        Annotations\EventBussMessage::class
    ];
}
