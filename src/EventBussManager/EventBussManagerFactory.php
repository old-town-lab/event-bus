<?php
/**
 * @link https://github.com/old-town/event-buss
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBuss\EventBussManager;
use OldTown\EventBuss\Driver\EventBussDriverInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\MutableCreationOptionsInterface;
use Zend\ServiceManager\MutableCreationOptionsTrait;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class EventBussManager
 *
 * @package OldTown\EventBuss\EventBussManager
 */
class EventBussManagerFactory implements FactoryInterface, MutableCreationOptionsInterface
{
    use MutableCreationOptionsTrait;

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return EventBussManager
     *
     * @throws \OldTown\EventBuss\EventBussManager\Exception\InvalidEventBussManagerConfigException
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $options = $this->getCreationOptions();
        if (!array_key_exists(ManagerInfoContainer::DRIVER, $options)) {
            $errMsg = sprintf('Отсутствует секция %s в конфиге', $options);
            throw new Exception\InvalidEventBussManagerConfigException($errMsg);
        }

        $driverName = $options[ManagerInfoContainer::DRIVER];

        /** @var EventBussDriverInterface $driver */
        $driver = $serviceLocator->get("eventbuss.driver.{$driverName}");

        $eventBussManager = new EventBussManager($driver);

        return $eventBussManager;
    }
}
