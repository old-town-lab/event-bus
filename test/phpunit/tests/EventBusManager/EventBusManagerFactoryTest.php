<?php
/**
 * @link https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\PhpUnit\Test\EventBusManager;

use OldTown\EventBus\PhpUnit\TestData\TestPaths;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use OldTown\EventBus\EventBusManager\EventBusPluginManager;
use OldTown\EventBus\EventBusManager\EventBusManagerFacade;
use Zend\ServiceManager\Exception\ExceptionInterface as ServiceManagerException;
use OldTown\EventBus\EventBusManager\Exception\InvalidEventBusManagerConfigException;
use OldTown\EventBus\EventBusManager\EventBusManagerFactory;
use Zend\ServiceManager\ServiceLocatorInterface;


/**
 * Class EventBusManagerFactoryTest
 *
 * @package OldTown\EventBus\PhpUnit\Test\EventBusManagerFacade
 */
class EventBusManagerFactoryTest extends AbstractHttpControllerTestCase
{
    /**
     * Создаем стандартного EventBusManagerFacade
     *
     */
    public function testCreateEventBusDefaultManager()
    {
        /** @noinspection PhpIncludeInspection */
        $this->setApplicationConfig(
            include TestPaths::getApplicationConfig()
        );
        /** @var EventBusPluginManager $eventBusPluginManager */
        $eventBusPluginManager = $this->getApplicationServiceLocator()->get('eventBusPluginManager');

        static::assertInstanceOf(EventBusPluginManager::class, $eventBusPluginManager);

        $config = [
            'driver' => 'default'
        ];
        $eventBusManager = $eventBusPluginManager->get('default', $config);

        static::assertInstanceOf(EventBusManagerFacade::class, $eventBusManager);
    }


    /**
     *  Создаем стандартного EventBusManagerFacade. Отсутствует секция driver в конфиге
     *
     */
    public function testCreateEventBusDefaltManagerSectionOfTheDriverIsAbsent()
    {
        /** @noinspection PhpIncludeInspection */
        $this->setApplicationConfig(
            include TestPaths::getApplicationConfig()
        );
        /** @var EventBusPluginManager $eventBusPluginManager */
        $eventBusPluginManager = $this->getApplicationServiceLocator()->get('eventBusPluginManager');

        static::assertInstanceOf(EventBusPluginManager::class, $eventBusPluginManager);

        try {
            $eventBusPluginManager->get('default');
        } catch (ServiceManagerException $e) {
            $prevException = $e->getPrevious();
            static::assertInstanceOf(InvalidEventBusManagerConfigException::class, $prevException);

            $actualMessage = $prevException->getMessage();
            $expectedMessage = 'Отсутствует секция driver в конфиге';
            static::assertEquals($expectedMessage, $actualMessage);
        }
    }


    /**
     * @expectedException \OldTown\EventBus\EventBusManager\Exception\RuntimeException
     * @expectedExceptionMessage Не удалось получить ServiceLocator
     *
     *  Создаем стандартного EventBusManagerFacade. Отсуттсвует сервис локатор приложения
     *
     */
    public function testCreateEventBusDefaltManagerNoServiceLocatorApp()
    {
        /** @noinspection PhpIncludeInspection */
        $this->setApplicationConfig(
            include TestPaths::getApplicationConfig()
        );
        $this->getApplication();

        $options = [
            'driver' => 'default'
        ];
        $factory = new EventBusManagerFactory();
        $factory->setCreationOptions($options);

        /** @var ServiceLocatorInterface $serviceLocator */
        $serviceLocator = $this->getMock(ServiceLocatorInterface::class);
        $factory->createService($serviceLocator);
    }
}
