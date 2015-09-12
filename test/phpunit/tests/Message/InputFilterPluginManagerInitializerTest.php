<?php
/**
 * @link https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\PhpUnit\Test\Message;

use OldTown\EventBus\Message\InputFilterPluginManagerAwareInterface;
use OldTown\EventBus\Message\InputFilterPluginManagerInitializer;
use PHPUnit_Framework_TestCase;
use Zend\InputFilter\InputFilterPluginManager;
use PHPUnit_Framework_MockObject_MockObject;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class InputFilterPluginManagerInitializerTest
 *
 * @package OldTown\EventBus\PhpUnit\Test\Message
 */
class InputFilterPluginManagerInitializerTest extends PHPUnit_Framework_TestCase
{
    /**
     *  Тестируем иньекцию inputFilterManager
     */
    public function testInitialize()
    {
        $inputFilterManager = $this->getMock(InputFilterPluginManager::class);

        /** @var ServiceLocatorInterface|PHPUnit_Framework_MockObject_MockObject $serviceLocator */
        $serviceLocator = $this->getMock(ServiceLocatorInterface::class);
        $serviceLocator->expects(static::once())
                       ->method('get')
                       ->with(static::equalTo('InputFilterManager'))
                       ->will(static::returnValue($inputFilterManager));

        /** @var AbstractPluginManager $pluginManager */
        $pluginManager = $this->getMockForAbstractClass(AbstractPluginManager::class);
        $pluginManager->setServiceLocator($serviceLocator);

        /** @var InputFilterPluginManagerAwareInterface|PHPUnit_Framework_MockObject_MockObject  $obj */
        $obj = $this->getMock(InputFilterPluginManagerAwareInterface::class, get_class_methods(InputFilterPluginManagerAwareInterface::class));
        $obj->expects(static::once())
            ->method('setInputFilterPluginManager')
            ->with(static::equalTo($inputFilterManager));


        $inizializer = new InputFilterPluginManagerInitializer();
        $inizializer->initialize($obj, $pluginManager);
    }

    /**
     *  Тестируем иньекцию inputFilterManager
     *
     * @expectedException \OldTown\EventBus\Message\Exception\RuntimeException
     * @expectedExceptionMessage Не удалось получить ServiceLocator
     */
    public function testInitializeInvalidServiceLocator()
    {
        /** @var ServiceLocatorInterface|PHPUnit_Framework_MockObject_MockObject $serviceLocator */
        $serviceLocator = $this->getMock(ServiceLocatorInterface::class);

        /** @var InputFilterPluginManagerAwareInterface  $obj */
        $obj = $this->getMock(InputFilterPluginManagerAwareInterface::class);

        $inizializer = new InputFilterPluginManagerInitializer();
        $inizializer->initialize($obj, $serviceLocator);
    }
}
