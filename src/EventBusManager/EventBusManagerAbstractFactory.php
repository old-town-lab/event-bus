<?php
/**
 * @link https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\EventBusManager;

use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use OldTown\EventBus\Module;

/**
 * Class ServiceAbstractFactory
 *
 * @package OldTown\EventBus\Factory
 */
class EventBusManagerAbstractFactory implements AbstractFactoryInterface
{
    /**
     * Контейнеры с информацие о EventBusManagerFacade
     *
     * @var ManagerInfoContainer[]
     */
    protected $managerInfoContainer = [];

    /**
     * Determine if we can create a service with name
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param $name
     * @param $requestedName
     * @return bool
     *
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     * @throws \OldTown\EventBus\EventBusManager\Exception\ErrorCreateEventBusManagerException
     */
    public function canCreateServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        $flag = 0 === strpos($name, 'eventbus.manager.');
        if ($flag) {
            $container = $this->getManagerInfoContainer($name, $serviceLocator);
            if (!$container instanceof ManagerInfoContainer) {
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
     * @return EventBusManagerInterface
     *
     * @throws \Zend\ServiceManager\Exception\ServiceNotCreatedException
     * @throws \OldTown\EventBus\EventBusManager\Exception\RuntimeException
     * @throws \Zend\ServiceManager\Exception\RuntimeException
     * @throws \OldTown\EventBus\EventBusManager\Exception\ErrorCreateEventBusManagerException
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     * @throws \OldTown\EventBus\EventBusManager\Exception\ErrorCreateEventBusManagerException
     */
    public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        $container = $this->getManagerInfoContainer($name, $serviceLocator);

        $pluginName = $container->getPluginName();

        /** @var EventBusPluginManager $eventBusPluginManager */
        $eventBusPluginManager = $serviceLocator->get(EventBusPluginManager::class);

        $pluginConfig = $container->getPluginConfig();
        $eventBusManager = $eventBusPluginManager->get($pluginName, $pluginConfig);

        return $eventBusManager;
    }

    /**
     *
     *
     * @param                         $name
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return ManagerInfoContainer|null
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     * @throws \OldTown\EventBus\EventBusManager\Exception\ErrorCreateEventBusManagerException
     */
    protected function getManagerInfoContainer($name, ServiceLocatorInterface $serviceLocator)
    {
        if (array_key_exists($name, $this->managerInfoContainer)) {
            return $this->managerInfoContainer[$name];
        }

        /** @var Module $module */
        $module = $serviceLocator->get(Module::class);

        $stackName = explode('.', $name);
        if (3 !== count($stackName)) {
            $errMsg = sprintf('Некорректный формат имени сервиса: %s', $name);
            throw new Exception\ErrorCreateEventBusManagerException($errMsg);
        }

        $serviceName =  $stackName[2];

        $evenBusManagerOptions = $module->getModuleOptions()->getEventBusManager();

        if (!array_key_exists($serviceName, $evenBusManagerOptions)) {
            $container = null;
        } else {
            $container = new ManagerInfoContainer($evenBusManagerOptions[$serviceName]);
        }
        $this->managerInfoContainer[$name] = $container;

        return $this->managerInfoContainer[$name];
    }
}
