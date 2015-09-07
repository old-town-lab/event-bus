<?php
/**
 * @link https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\EventBusManager;

use OldTown\EventBus\Driver\EventBusDriverInterface;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\MutableCreationOptionsInterface;
use Zend\ServiceManager\MutableCreationOptionsTrait;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class EventBusManagerFacade
 *
 * @package OldTown\EventBus\EventBusManagerFacade
 */
class EventBusManagerFactory implements FactoryInterface, MutableCreationOptionsInterface
{
    use MutableCreationOptionsTrait;

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return EventBusManagerFacade
     *
     * @throws \OldTown\EventBus\EventBusManager\Exception\InvalidEventBusManagerConfigException
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     * @throws \OldTown\EventBus\EventBusManager\Exception\RuntimeException
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $options = $this->getCreationOptions();
        if (!array_key_exists(ManagerInfoContainer::DRIVER, $options)) {
            $errMsg = sprintf('Отсутствует секция %s в конфиге', ManagerInfoContainer::DRIVER);
            throw new Exception\InvalidEventBusManagerConfigException($errMsg);
        }

        $driverName = $options[ManagerInfoContainer::DRIVER];

        $appServiceLocator = null;
        if ($serviceLocator instanceof AbstractPluginManager) {
            $appServiceLocator = $serviceLocator->getServiceLocator();
        }

        if (!$appServiceLocator instanceof ServiceLocatorInterface) {
            $errMsg = 'Не удалось получить ServiceLocator';
            throw new Exception\RuntimeException($errMsg);
        }


        /** @var EventBusDriverInterface $driver */
        $driver = $appServiceLocator->get("eventbus.driver.{$driverName}");

        $eventBusManager = new EventBusManagerFacade($driver);

        return $eventBusManager;
    }
}
