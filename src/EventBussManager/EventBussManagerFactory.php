<?php
/**
 * @link https://github.com/old-town/event-buss
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBuss\EventBussManager;

use OldTown\EventBuss\Driver\EventBussDriverInterface;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\MutableCreationOptionsInterface;
use Zend\ServiceManager\MutableCreationOptionsTrait;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class EventBussManagerFacade
 *
 * @package OldTown\EventBuss\EventBussManagerFacade
 */
class EventBussManagerFactory implements FactoryInterface, MutableCreationOptionsInterface
{
    use MutableCreationOptionsTrait;

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
*@return EventBussManagerFacade
     *
     * @throws \OldTown\EventBuss\EventBussManager\Exception\InvalidEventBussManagerConfigException
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     * @throws \OldTown\EventBuss\EventBussManager\Exception\RuntimeException
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $options = $this->getCreationOptions();
        if (!array_key_exists(ManagerInfoContainer::DRIVER, $options)) {
            $errMsg = sprintf('Отсутствует секция %s в конфиге', ManagerInfoContainer::DRIVER);
            throw new Exception\InvalidEventBussManagerConfigException($errMsg);
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


        /** @var EventBussDriverInterface $driver */
        $driver = $appServiceLocator->get("eventbuss.driver.{$driverName}");

        $eventBussManager = new EventBussManagerFacade($driver);

        return $eventBussManager;
    }
}
