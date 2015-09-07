<?php
/**
 * @link https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\Driver;

use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\MutableCreationOptionsInterface;
use Zend\ServiceManager\MutableCreationOptionsTrait;
use Zend\ServiceManager\ServiceLocatorInterface;
use OldTown\EventBus\Module;
use Zend\Stdlib\ArrayUtils;
use OldTown\EventBus\MetadataReader\EventBussMetadataReaderPluginManager;


/**
 * Class ServiceAbstractFactory
 *
 * @package OldTown\EventBus\Factory
 */
class EventBussPluginDriverAbstractFactory implements AbstractFactoryInterface, MutableCreationOptionsInterface
{
    use MutableCreationOptionsTrait;

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
        $flag = 0 === strpos($requestedName, __NAMESPACE__) && class_exists($requestedName);
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
     * @throws \OldTown\EventBus\Driver\Exception\RuntimeException
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     * @throws \OldTown\EventBus\Driver\Exception\ConnectionNotFoundException
     */
    public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        $options = $this->getCreationOptions();


        $appServiceLocator = null;
        if ($serviceLocator instanceof AbstractPluginManager) {
            $appServiceLocator = $serviceLocator->getServiceLocator();
        }

        if (!$appServiceLocator instanceof ServiceLocatorInterface) {
            $errMsg = 'Не удалось получить ServiceLocator';
            throw new Exception\RuntimeException($errMsg);
        }

        if (array_key_exists(DriverConfig::CONNECTION, $options) && is_string($options[DriverConfig::CONNECTION])) {
            $connectionName = $options[DriverConfig::CONNECTION];

            /** @var Module $module */
            $module = $appServiceLocator->get(Module::class);
            if (!$module instanceof Module) {
                $errMsg = sprintf('Не удалось получить модуль: %s', Module::class);
                throw new Exception\RuntimeException($errMsg);
            }

            $connections = $module->getModuleOptions()->getConnection();

            if (!array_key_exists($connectionName, $connections)) {
                $errMsg = sprintf('Отсутствует соеденение с именем: %s', $connectionName);
                throw new Exception\ConnectionNotFoundException($errMsg);
            }

            $connections = $connections[$connectionName];

            if (array_key_exists(DriverConfig::CONNECTION_CONFIG, $options) && is_array($options[DriverConfig::CONNECTION_CONFIG])) {
                $options[DriverConfig::CONNECTION_CONFIG] = ArrayUtils::merge($connections, $options[DriverConfig::CONNECTION_CONFIG]);
            } else {
                $options[DriverConfig::CONNECTION_CONFIG] = $connections;
            }
        }

        $metadataReaderPluginManager = $appServiceLocator->get(EventBussMetadataReaderPluginManager::class);

        $driver = new $requestedName($options, $metadataReaderPluginManager);
        return $driver;
    }
}
