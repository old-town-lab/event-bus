<?php
/**
 * @link https://github.com/old-town/event-buss
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBuss\Factory;

use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class ServiceAbstractFactory
 *
 * @package OldTown\EventBuss\Factory
 */
class ServiceAbstractFactory implements AbstractFactoryInterface
{
    /**
     * Determine if we can create a service with name
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param $name
     * @param $requestedName
     * @return bool
     */
    public function canCreateServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        $flag = 0 === strpos($name, 'eventbuss.');
        return $flag;
    }

    /**
     * Create service with name
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param $name
     * @param $requestedName
     *
     * @throws Exception\CreateServiceException
     * @return mixed
     */
    public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        $stackName = explode('.', $name);
        if (3 !== count($stackName)) {
            $errMsg = sprintf('Некорректный формат имени сервиса: %s', $name);
            throw new Exception\CreateServiceException($errMsg);
        }

        $serviceType = $stackName[1];
        $serviceName =  $stackName[2];

        switch ($serviceType) {

            default:
                $errMsg = sprintf('Неизвестный тип сервиса: %s', $serviceType);
                throw new Exception\CreateServiceException($errMsg);
        }
    }
}
