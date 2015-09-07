<?php
/**
 * @link https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\EventBussManager;

use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use OldTown\EventBus\Module;

/**
 * Class ServiceAbstractFactory
 *
 * @package OldTown\EventBus\Factory
 */
class EventBussManagerAbstractFactory implements AbstractFactoryInterface
{
    /**
     * Контейнеры с информацие о EventBussManagerFacade
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
     * @throws \OldTown\EventBus\EventBussManager\Exception\ErrorCreateEventBussManagerException
     */
    public function canCreateServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        $flag = 0 === strpos($name, 'eventbuss.manager.');
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
     * @return EventBussManagerInterface
     *
     * @throws \Zend\ServiceManager\Exception\ServiceNotCreatedException
     * @throws \OldTown\EventBus\EventBussManager\Exception\RuntimeException
     * @throws \Zend\ServiceManager\Exception\RuntimeException
     * @throws \OldTown\EventBus\EventBussManager\Exception\ErrorCreateEventBussManagerException
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     * @throws \OldTown\EventBus\EventBussManager\Exception\ErrorCreateEventBussManagerException
     */
    public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        $container = $this->getManagerInfoContainer($name, $serviceLocator);

        $pluginName = $container->getPluginName();

        /** @var EventBussPluginManager $eventBussPluginManager */
        $eventBussPluginManager = $serviceLocator->get(EventBussPluginManager::class);

        $pluginConfig = $container->getPluginConfig();
        $eventBussManager = $eventBussPluginManager->get($pluginName, $pluginConfig);

        return $eventBussManager;
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
     * @throws \OldTown\EventBus\EventBussManager\Exception\ErrorCreateEventBussManagerException
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
            throw new Exception\ErrorCreateEventBussManagerException($errMsg);
        }

        $serviceName =  $stackName[2];

        $evenBussManagerOptions = $module->getModuleOptions()->getEventBussManager();

        if (!array_key_exists($serviceName, $evenBussManagerOptions)) {
            $container = null;
        } else {
            $container = new ManagerInfoContainer($evenBussManagerOptions[$serviceName]);
        }
        $this->managerInfoContainer[$name] = $container;

        return $this->managerInfoContainer[$name];
    }
}
