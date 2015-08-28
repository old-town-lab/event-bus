<?php
/**
 * @link https://github.com/old-town/event-buss
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBuss\Options;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use OldTown\EventBuss\Module;

/**
 * Class ModuleOptions
 * @package ContractModule\Options
 */
class ModuleOptionsFactory implements FactoryInterface
{

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return ModuleOptions
     *
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var array $appConfig */
        $appConfig = $serviceLocator->get('config');
        $config = [];
        if (array_key_exists(Module::CONFIG_KEY, $appConfig)) {
            $config = $appConfig[Module::CONFIG_KEY];
        }
        $moduleOptions = new ModuleOptions($config);
        return $moduleOptions;
    }
}