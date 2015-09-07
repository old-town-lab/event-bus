<?php
/**
 * @link https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\PhpUnit\Test\EventBussManager;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use OldTown\EventBus\EventBussManager\EventBussPluginManager;
use OldTown\EventBus\EventBussManager\EventBussManagerFacade;
use Zend\ServiceManager\Exception\ExceptionInterface as ServiceManagerException;
use OldTown\EventBus\EventBussManager\Exception\InvalidEventBussManagerConfigException;
use OldTown\EventBus\EventBussManager\EventBussManagerFactory;
use Zend\ServiceManager\ServiceLocatorInterface;


/**
 * Class EventBussManagerFactoryTest
 *
 * @package OldTown\EventBus\PhpUnit\Test\EventBussManagerFacade
 */
class EventBussManagerFactoryTest extends AbstractHttpControllerTestCase
{
    /**
     * Создаем стандартного EventBussManagerFacade
     *
     */
    public function testCreateEventBussDefaultManager()
    {
        $this->setApplicationConfig(
            include __DIR__ . '/../../_files/application.config.php'
        );
        /** @var EventBussPluginManager $eventBussPluginManager */
        $eventBussPluginManager = $this->getApplicationServiceLocator()->get('eventBussPluginManager');

        static::assertInstanceOf(EventBussPluginManager::class, $eventBussPluginManager);

        $config = [
            'driver' => 'default'
        ];
        $eventBussManager = $eventBussPluginManager->get('default', $config);

        static::assertInstanceOf(EventBussManagerFacade::class, $eventBussManager);
    }


    /**
     *  Создаем стандартного EventBussManagerFacade. Отсутствует секция driver в конфиге
     *
     */
    public function testCreateEventBussDefaltManagerSectionOfTheDriverIsAbsent()
    {
        $this->setApplicationConfig(
            include __DIR__ . '/../../_files/application.config.php'
        );
        /** @var EventBussPluginManager $eventBussPluginManager */
        $eventBussPluginManager = $this->getApplicationServiceLocator()->get('eventBussPluginManager');

        static::assertInstanceOf(EventBussPluginManager::class, $eventBussPluginManager);

        try {
            $eventBussPluginManager->get('default');
        } catch (ServiceManagerException $e) {
            $prevException = $e->getPrevious();
            static::assertInstanceOf(InvalidEventBussManagerConfigException::class, $prevException);

            $actualMessage = $prevException->getMessage();
            $expectedMessage = 'Отсутствует секция driver в конфиге';
            static::assertEquals($expectedMessage, $actualMessage);
        }
    }


    /**
     * @expectedException \OldTown\EventBus\EventBussManager\Exception\RuntimeException
     * @expectedExceptionMessage Не удалось получить ServiceLocator
     *
     *  Создаем стандартного EventBussManagerFacade. Отсуттсвует сервис локатор приложения
     *
     */
    public function testCreateEventBussDefaltManagerNoServiceLocatorApp()
    {
        $this->setApplicationConfig(
            include __DIR__ . '/../../_files/application.config.php'
        );
        $this->getApplication();

        $options = [
            'driver' => 'default'
        ];
        $factory = new EventBussManagerFactory();
        $factory->setCreationOptions($options);

        /** @var ServiceLocatorInterface $serviceLocator */
        $serviceLocator = $this->getMock(ServiceLocatorInterface::class);
        $factory->createService($serviceLocator);
    }
}
