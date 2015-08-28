<?php
/**
 * @link https://github.com/old-town/event-buss
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBuss\Driver;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\MutableCreationOptionsInterface;
use Zend\ServiceManager\MutableCreationOptionsTrait;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class DriverChain
 *
 * @package OldTown\EventBuss\Driver
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
        $options = $this->getCreationOptions();
        $eventBussManager = new DriverChain($options);

        return $eventBussManager;
    }
}
