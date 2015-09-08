<?php
/**
 * @link https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\Driver\RabbitMqDriver\MetadataReader;

use OldTown\EventBus\MetadataReader\AbstractAnnotationReader;


/**
 * Class AnnotationReader
 *
 * @package OldTown\EventBus\Driver\RabbitMqDriver\MetadataReader
 */
class AnnotationReader extends AbstractAnnotationReader
{
    /**
     * @var array
     */
    protected $messageAnnotationClasses = [
        Annotations\EventBusMessage::class
    ];

    /**
     * Хранилище метаданных для класса
     *
     * @var MetadataInterface[]
     */
    protected $metadataForClass = [];

    /**
     * @param string $className
     *
     * @return MetadataInterface
     *
     * @throws \OldTown\EventBus\Driver\RabbitMqDriver\MetadataReader\Exception\InvalidClassException
     */
    public function loadMetadataForClass($className)
    {
        if (array_key_exists($className, $this->metadataForClass)) {
            return $this->metadataForClass[$className];
        }
        $annotations = $this->getClassAnnotation($className);

        $eventBusMessageAnnotation = null;
        foreach ($annotations as $annotation) {
            if ($annotation instanceof Annotations\EventBusMessage) {
                $eventBusMessageAnnotation = $annotation;
                break;
            }
        }
        if (null === $eventBusMessageAnnotation) {
            $errMsg = sprintf('Класс не содержит необходимых метаданных : %s', $className);
            throw new Exception\InvalidClassException($errMsg);
        }

        $metadata = new Metadata($eventBusMessageAnnotation);

        $this->metadataForClass[$className] = $metadata;

        return $this->metadataForClass[$className];
    }
}
