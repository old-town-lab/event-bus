<?php
/**
 * @link https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\Driver\RabbitMqDriver\MetadataReader;

use OldTown\EventBus\Driver\DriverConfig;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\MutableCreationOptionsInterface;
use Zend\ServiceManager\MutableCreationOptionsTrait;
use Zend\ServiceManager\ServiceLocatorInterface;


/**
 * Class AnnotationReader
 *
 * @package OldTown\EventBus\Driver\RabbitMqDriver\MetadataReader
 */
class AnnotationReaderFactory  implements FactoryInterface, MutableCreationOptionsInterface
{
    use MutableCreationOptionsTrait;


    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return AnnotationReader
     *
     * @throws \OldTown\EventBus\Driver\RabbitMqDriver\MetadataReader\Exception\InvalidPathException
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $options = $this->getCreationOptions();
        if (!(array_key_exists(DriverConfig::PATHS, $options) && is_array($options[DriverConfig::PATHS]))) {
            $errMsg = sprintf('Некорректная секция в конфиге: %s', DriverConfig::PATHS);
            throw new Exception\InvalidPathException($errMsg);
        }
        $eventBusManager = new AnnotationReader($options);

        return $eventBusManager;
    }
}
