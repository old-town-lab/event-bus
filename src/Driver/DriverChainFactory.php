<?php
/**
 * @link https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\Driver;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\MutableCreationOptionsInterface;
use Zend\ServiceManager\MutableCreationOptionsTrait;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class DriverChain
 *
 * @package OldTown\EventBus\Driver
 */
class DriverChainFactory  implements FactoryInterface, MutableCreationOptionsInterface
{
    use MutableCreationOptionsTrait;


    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return DriverChain
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var EventBusDriverPluginManager $serviceLocator */
        $options = $this->getCreationOptions();
        $eventBusManager = new DriverChain($options, $serviceLocator);

        return $eventBusManager;
    }
}
