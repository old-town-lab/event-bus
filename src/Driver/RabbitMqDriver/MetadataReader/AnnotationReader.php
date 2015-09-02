<?php
/**
 * @link https://github.com/old-town/event-buss
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBuss\Driver\RabbitMqDriver\MetadataReader;

use OldTown\EventBuss\MetadataReader\AbstractAnnotationReader;
use OldTown\EventBuss\MetadataReader\MetadataInterface;

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

    /**
     * Хранилище метаданных для класса
     *
     * @var Metadata[]
     */
    protected $metadataForClass = [];

    /**
     * @param string $className
     *
     * @return MetadataInterface
     *
     * @throws \OldTown\EventBuss\Driver\RabbitMqDriver\MetadataReader\Exception\InvalidClassException
     */
    public function loadMetadataForClass($className)
    {
        if (array_key_exists($className, $this->metadataForClass)) {
            return $this->metadataForClass[$className];
        }
        $annotations = $this->getClassAnnotation($className);

        $eventBussMessageAnnotation = null;
        foreach ($annotations as $annotation) {
            if ($annotation instanceof Annotations\EventBussMessage) {
                $eventBussMessageAnnotation = $annotation;
                break;
            }
        }
        if (null === $eventBussMessageAnnotation) {
            $errMsg = sprintf('Класс не содержит необходимы метаданных : %s', $className);
            throw new Exception\InvalidClassException($errMsg);
        }

        $metadata = new Metadata($eventBussMessageAnnotation);

        $this->metadataForClass[$className] = $metadata;

        return $this->metadataForClass[$className];
    }
}
