<?php
/**
 * @link https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\Message;

use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\MutableCreationOptionsInterface;
use Zend\ServiceManager\MutableCreationOptionsTrait;
use Zend\ServiceManager\ServiceLocatorInterface;
use ReflectionClass;

/**
 * Class PluginMessageAbstractFactory
 *
 * @package OldTown\EventBus\Message
 */
class PluginMessageAbstractFactory implements AbstractFactoryInterface, MutableCreationOptionsInterface
{
    use MutableCreationOptionsTrait;

    /**
     * Determine if we can create a service with name
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param $name
     * @param $requestedName
     * @return bool
     *
     */
    public function canCreateServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        if (!class_exists($requestedName)) {
            return false;
        }
        $implements = class_implements($requestedName);
        if (!array_key_exists(MessageInterface::class, $implements)) {
            return false;
        }
        $r = new ReflectionClass($requestedName);
        if (!$r->isInstantiable()) {
            return false;
        }


        return true;
    }

    /**
     * Create service with name
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param $name
     * @param $requestedName
     *
     * @return MessageInterface
     * @throws Exception\RuntimeException
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     */
    public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        $parents = class_parents($requestedName);
        $r = new ReflectionClass($requestedName);

        if (array_key_exists(AbstractMessage::class, $parents)) {
            $appServiceLocator = null;
            if ($serviceLocator instanceof AbstractPluginManager) {
                $appServiceLocator = $serviceLocator->getServiceLocator();
            }

            if (!$appServiceLocator instanceof ServiceLocatorInterface) {
                $errMsg = 'Не удалось получить ServiceLocator';
                throw new Exception\RuntimeException($errMsg);
            }

            $validatorPluginManager = $appServiceLocator->get('ValidatorManager');
            $hydratorPluginManager = $appServiceLocator->get('HydratorManager');

            $service = $r->newInstance($hydratorPluginManager, $validatorPluginManager);
        } else {
            $service = $r->newInstance();
        }

        return $service;
    }
}
