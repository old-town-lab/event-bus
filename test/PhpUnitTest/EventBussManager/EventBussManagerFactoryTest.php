<?php
/**
 * @link https://github.com/old-town/event-buss
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBuss\PhpUnitTest\EventBussManager;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use OldTown\EventBuss\EventBussManager\EventBussPluginManager;
use OldTown\EventBuss\EventBussManager\EventBussManager;
use Zend\ServiceManager\Exception\ExceptionInterface as ServiceManagerException;
use OldTown\EventBuss\EventBussManager\Exception\InvalidEventBussManagerConfigException;
use OldTown\EventBuss\EventBussManager\EventBussManagerFactory;
use Zend\ServiceManager\ServiceLocatorInterface;


/**
 * Class EventBussManagerFactoryTest
 *
 * @package OldTown\EventBuss\PhpUnitTest\EventBussManager
 */
class EventBussManagerFactoryTest extends AbstractHttpControllerTestCase
{
    /**
     * Создаем стандартного EventBussManager
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

        static::assertInstanceOf(EventBussManager::class, $eventBussManager);
    }


    /**
     *  Создаем стандартного EventBussManager. Отсутствует секция driver в конфиге
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
     * @expectedException \OldTown\EventBuss\EventBussManager\Exception\RuntimeException
     * @expectedExceptionMessage Не удалось получить ServiceLocator
     *
     *  Создаем стандартного EventBussManager. Отсуттсвует сервис локатор приложения
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
