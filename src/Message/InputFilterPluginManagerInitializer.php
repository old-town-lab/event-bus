<?php
/**
 * @link https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\Message;

use Zend\InputFilter\InputFilterPluginManager;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\InitializerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;


/**
 * Class InputFilterPluginManagerInitializer
 *
 * @package OldTown\EventBus\Message
 */
class InputFilterPluginManagerInitializer implements InitializerInterface
{
    /**
     * @param                         $instance
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return mixed
     *
     * @throws Exception\RuntimeException
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     */
    public function initialize($instance, ServiceLocatorInterface $serviceLocator)
    {
        if ($instance instanceof InputFilterPluginManagerAwareInterface) {
            $appServiceLocator = null;
            if ($serviceLocator instanceof AbstractPluginManager) {
                $appServiceLocator = $serviceLocator->getServiceLocator();
            }

            if (!$appServiceLocator instanceof ServiceLocatorInterface) {
                $errMsg = 'Не удалось получить ServiceLocator';
                throw new Exception\RuntimeException($errMsg);
            }

            /** @var InputFilterPluginManager $inputFilterManager */
            $inputFilterManager = $appServiceLocator->get('InputFilterManager');

            $instance->setInputFilterPluginManager($inputFilterManager);
        }
    }
}
