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
class EventBussDriverAbstractFactory implements AbstractFactoryInterface
{
    /**
     * Контейнеры с информацие о EventBussManagerFacade
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
     * @return bool
     *
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     * @throws \OldTown\EventBus\Driver\Exception\ErrorCreateEventBussDriverException
     */
    public function canCreateServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        $flag = 0 === strpos($name, 'eventbuss.driver.');
        if ($flag) {
            $container = $this->getDriverConfigs($name, $serviceLocator);
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
     * @return EventBussDriverInterface
     *
     * @throws \Zend\ServiceManager\Exception\ServiceNotCreatedException
     * @throws \OldTown\EventBus\EventBussManager\Exception\RuntimeException
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     * @throws \Zend\ServiceManager\Exception\RuntimeException
     * @throws \OldTown\EventBus\Driver\Exception\ErrorCreateEventBussDriverException
     */
    public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        $container = $this->getDriverConfigs($name, $serviceLocator);

        $pluginName = $container->getPluginName();

        /** @var EventBussDriverPluginManager $eventBussDriverPluginManager */
        $eventBussDriverPluginManager = $serviceLocator->get(EventBussDriverPluginManager::class);

        $pluginConfig = $container->getPluginConfig();
        $eventBussManager = $eventBussDriverPluginManager->get($pluginName, $pluginConfig);

        return $eventBussManager;
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
     * @throws \OldTown\EventBus\Driver\Exception\ErrorCreateEventBussDriverException
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
            throw new Exception\ErrorCreateEventBussDriverException($errMsg);
        }

        $serviceName =  $stackName[2];

        $evenBussManagerOptions = $module->getModuleOptions()->getDriver();

        if (!array_key_exists($serviceName, $evenBussManagerOptions)) {
            $container = null;
        } else {
            $container = new DriverConfig($evenBussManagerOptions[$serviceName]);
        }
        $this->driverConfigs[$name] = $container;

        return $this->driverConfigs[$name];
    }
}
