<?php
/**
 * @link https://github.com/old-town/event-buss
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBuss;


use OldTown\EventBuss\EventBussManager\EventBussPluginManager;
use OldTown\EventBuss\Options\ModuleOptions;
use Zend\EventManager\EventInterface;
use Zend\ModuleManager\Listener\ServiceListenerInterface;
use Zend\ModuleManager\ModuleManager;
use Zend\ModuleManager\ModuleManagerInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Loader\StandardAutoloader;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\LocatorRegisteredInterface;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\ModuleManager\Feature\InitProviderInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use OldTown\EventBuss\EventBussManager\EventBussManagerProviderInterface;


/**
 * Class Module
 *
 * @package OldTown\EventBuss
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
    const CONFIG_KEY = 'event_buss';

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
            StandardAutoloader::class => array(
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
     * @throws \OldTown\EventBuss\Exception\ErrorInitModuleException
     */
    public function init(ModuleManagerInterface $manager)
    {
        if (!$manager instanceof ModuleManager) {
            $errMsg =sprintf('Менеджер модулей должен реализовывать %s', $manager);
            throw new Exception\ErrorInitModuleException($errMsg);
        }
        /** @var ModuleManager $manager */

        /** @var ServiceLocatorInterface $sm */
        $sm = $manager->getEvent()->getParam('ServiceManager');

        /** @var ServiceListenerInterface $serviceListener */
        $serviceListener = $sm->get('ServiceListener');
        $serviceListener->addServiceManager(
            'eventBussPluginManager',
            'event_buss_manager',
            EventBussManagerProviderInterface::class,
            'getEventBussManagerConfig'
        );
    }
} 