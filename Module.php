<?php
/**
 * @link https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus;


use OldTown\EventBus\Driver\EventBusDriverProviderInterface;
use OldTown\EventBus\Message\EventBusMessageProviderInterface;
use OldTown\EventBus\MetadataReader\EventBusMetadataReaderProviderInterface;
use OldTown\EventBus\Options\ModuleOptions;
use OldTown\EventBus\EventBusManager\EventBusManagerProviderInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\EventManager\EventInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ModuleManager\Listener\ServiceListenerInterface;
use Zend\ModuleManager\ModuleManager;
use Zend\ModuleManager\ModuleManagerInterface;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\LocatorRegisteredInterface;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\ModuleManager\Feature\InitProviderInterface;


/**
 * Class Module
 *
 * @package OldTown\EventBus
 */
class Module implements
    LocatorRegisteredInterface,
    BootstrapListenerInterface,
    ConfigProviderInterface,
    AutoloaderProviderInterface,
    ServiceLocatorAwareInterface,
    InitProviderInterface
{
    use ServiceLocatorAwareTrait;

    /**
     * Имя секции в конфиги приложения
     *
     * @var string
     */
    const CONFIG_KEY = 'event_bus';

    /**
     * @param EventInterface $e
     *
     * @return array|void
     */
    public function onBootstrap(EventInterface $e)
    {
        /** @var MvcEvent $e */
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        $this->setServiceLocator($e->getApplication()->getServiceManager());
    }


    /**
     * @return mixed
     */
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    /**
     * @return array
     */
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    /**
     *
     * @return ModuleOptions
     *
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     */
    public function getModuleOptions()
    {
        $moduleOptions = $this->getServiceLocator()->get(ModuleOptions::class);

        return $moduleOptions;
    }


    /**
     *
     * @param ModuleManagerInterface $manager
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     * @throws \OldTown\EventBus\Exception\ErrorInitModuleException
     */
    public function init(ModuleManagerInterface $manager)
    {
        if (!$manager instanceof ModuleManager) {
            $errMsg =sprintf('Менеджер модулей должен реализовывать %s', ModuleManager::class);
            throw new Exception\ErrorInitModuleException($errMsg);
        }
        /** @var ModuleManager $manager */

        /** @var ServiceLocatorInterface $sm */
        $sm = $manager->getEvent()->getParam('ServiceManager');

        /** @var ServiceListenerInterface $serviceListener */
        $serviceListener = $sm->get('ServiceListener');
        $serviceListener->addServiceManager(
            'eventBusPluginManager',
            'event_bus_manager',
            EventBusManagerProviderInterface::class,
            'getEventBusManagerConfig'
        );
        $serviceListener->addServiceManager(
            'eventBusDriverManager',
            'event_bus_driver',
            EventBusDriverProviderInterface::class,
            'getEventBusDriverConfig'
        );
        $serviceListener->addServiceManager(
            'eventBusMetadataReaderManager',
            'event_bus_metadata_reader',
            EventBusMetadataReaderProviderInterface::class,
            'getEventBusMetadataReaderConfig'
        );
        $serviceListener->addServiceManager(
            'eventBusMessageManager',
            'event_bus_message',
            EventBusMessageProviderInterface::class,
            'getEventBusMessageConfig'
        );
    }
} 