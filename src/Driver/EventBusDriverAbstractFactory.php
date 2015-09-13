<?php
/**
 * @link https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\Driver;

use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use OldTown\EventBus\Module;

/**
 * Class ServiceAbstractFactory
 *
 * @package OldTown\EventBus\Factory
 */
class EventBusDriverAbstractFactory implements AbstractFactoryInterface
{
    /**
     * Контейнеры с информацие о EventBusManagerFacade
     *
     * @var DriverConfig[]
     */
    protected $driverConfigs = [];

    /**
     * Determine if we can create a service with name
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param $name
     * @param $requestedName
     *
     *@return bool
     *
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     * @throws \OldTown\EventBus\Driver\Exception\ErrorCreateEventBusDriverException
     */
    public function canCreateServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        $flag = 0 === strpos($name, 'eventbus.driver.');
        if ($flag) {
            $container = $this->getDriverConfigs($requestedName, $serviceLocator);
            if (!$container instanceof DriverConfig) {
                $flag = false;
            }
        }
        return $flag;
    }

    /**
     * Create service with name
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param $name
     * @param $requestedName
     *
     * @return EventBusDriverInterface
     *
     * @throws \Zend\ServiceManager\Exception\ServiceNotCreatedException
     * @throws \OldTown\EventBus\EventBusManager\Exception\RuntimeException
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     * @throws \Zend\ServiceManager\Exception\RuntimeException
     * @throws \OldTown\EventBus\Driver\Exception\ErrorCreateEventBusDriverException
     */
    public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        $container = $this->getDriverConfigs($requestedName, $serviceLocator);

        $pluginName = $container->getPluginName();

        /** @var EventBusDriverPluginManager $eventBusDriverPluginManager */
        $eventBusDriverPluginManager = $serviceLocator->get(EventBusDriverPluginManager::class);

        $pluginConfig = $container->getPluginConfig();
        $eventBusManager = $eventBusDriverPluginManager->get($pluginName, $pluginConfig);

        return $eventBusManager;
    }

    /**
     *
     *
     * @param                         $name
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return DriverConfig|null
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     * @throws \OldTown\EventBus\Driver\Exception\ErrorCreateEventBusDriverException
     */
    protected function getDriverConfigs($name, ServiceLocatorInterface $serviceLocator)
    {
        if (array_key_exists($name, $this->driverConfigs)) {
            return $this->driverConfigs[$name];
        }

        /** @var Module $module */
        $module = $serviceLocator->get(Module::class);

        $stackName = explode('.', $name);
        if (3 !== count($stackName)) {
            $errMsg = sprintf('Некорректный формат имени сервиса: %s', $name);
            throw new Exception\ErrorCreateEventBusDriverException($errMsg);
        }

        $serviceName =  $stackName[2];

        $evenBusManagerOptions = $module->getModuleOptions()->getDriver();

        if (!array_key_exists($serviceName, $evenBusManagerOptions)) {
            $container = null;
        } else {
            $container = new DriverConfig($evenBusManagerOptions[$serviceName]);
        }
        $this->driverConfigs[$name] = $container;

        return $this->driverConfigs[$name];
    }
}
