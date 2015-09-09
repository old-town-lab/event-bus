<?php
/**
 * @link https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\PhpUnit\Test\Message;

use OldTown\EventBus\Message\DummyMessage;
use OldTown\EventBus\Message\MessageInterface;
use OldTown\EventBus\Message\PluginMessageAbstractFactory;
use OldTown\EventBus\PhpUnit\TestData\TestPaths;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\ServiceManager;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use OldTown\EventBus\Message\EventBusMessagePluginManager;
use OldTown\EventBus\PhpUnit\TestData\Messages\FooExtendAbstractSimpleMessage;
use OldTown\EventBus\Message\AbstractSimpleMessage;


/**
 * Class AbstractMessageTest
 *
 * @package OldTown\EventBus\PhpUnit\Test\Message
 */
class PluginMessageAbstractFactoryFunctionalTest extends AbstractHttpControllerTestCase
{
    /**
     * Тест корректности определения того что сервис может быть создан фабрикой
     *
     */
    public function testCreateServiceExtendSimpleMessage()
    {
        /** @noinspection PhpIncludeInspection */
        $this->setApplicationConfig(
            include TestPaths::getApplicationConfig()
        );

        /** @var EventBusMessagePluginManager $manager */
        $manager = $this->getApplicationServiceLocator()->get(EventBusMessagePluginManager::class);

        $actual = $manager->get(FooExtendAbstractSimpleMessage::class);

        static::assertInstanceOf(FooExtendAbstractSimpleMessage::class, $actual);
    }

    /**
     * Тест корректности определения того что сервис может быть создан фабрикой
     *
     */
    public function testCreateServiceDummyMessage()
    {
        /** @noinspection PhpIncludeInspection */
        $this->setApplicationConfig(
            include TestPaths::getApplicationConfig()
        );

        /** @var EventBusMessagePluginManager $manager */
        $manager = $this->getApplicationServiceLocator()->get(EventBusMessagePluginManager::class);

        $actual = $manager->get(DummyMessage::class);

        static::assertInstanceOf(DummyMessage::class, $actual);
    }

    /**
     * Проверка ситуации когда указан несуществуйщи класс
     *
     * @expectedException \Zend\ServiceManager\Exception\ServiceNotFoundException
     * @expectedExceptionMessage OldTown\EventBus\Message\EventBusMessagePluginManager::get was unable to fetch or create an instance for Invalid Class Name
     */
    public function testCanCreateServiceWithInvalidClassName()
    {
        /** @noinspection PhpIncludeInspection */
        $this->setApplicationConfig(
            include TestPaths::getApplicationConfig()
        );

        /** @var EventBusMessagePluginManager $manager */
        $manager = $this->getApplicationServiceLocator()->get(EventBusMessagePluginManager::class);

        $manager->get('Invalid Class Name');
    }


    /**
     * Проверка ситуации когда указан несуществуйщи класс
     *
     * @expectedException \Zend\ServiceManager\Exception\ServiceNotFoundException
     * @expectedExceptionMessage OldTown\EventBus\Message\EventBusMessagePluginManager::get was unable to fetch or create an instance for OldTown\EventBus\Message\AbstractSimpleMessage
     */
    public function testCanCreateServiceWithAbstractClass()
    {
        /** @noinspection PhpIncludeInspection */
        $this->setApplicationConfig(
            include TestPaths::getApplicationConfig()
        );

        /** @var EventBusMessagePluginManager $manager */
        $manager = $this->getApplicationServiceLocator()->get(EventBusMessagePluginManager::class);

        $manager->get(AbstractSimpleMessage::class);
    }


    /**
     * Проверка ситуации когда указан несуществуйщи класс
     *
     * @expectedException \Zend\ServiceManager\Exception\ServiceNotFoundException
     * @expectedExceptionMessage OldTown\EventBus\Message\EventBusMessagePluginManager::get was unable to fetch or create an instance for stdClass
     */
    public function testCanCreateServiceWithNotImplementsMessageInterface()
    {
        /** @noinspection PhpIncludeInspection */
        $this->setApplicationConfig(
            include TestPaths::getApplicationConfig()
        );

        /** @var EventBusMessagePluginManager $manager */
        $manager = $this->getApplicationServiceLocator()->get(EventBusMessagePluginManager::class);

        $manager->get(\stdClass::class);
    }

    /**
     * Отсутствует сервис менеджер приложения
     *
     * @expectedException \OldTown\EventBus\Message\Exception\RuntimeException
     * @expectedExceptionMessage Не удалось получить ServiceLocator
     */
    public function testNoAppServiceLocator()
    {
        try {
            /** @noinspection PhpIncludeInspection */
            $this->setApplicationConfig(
                include TestPaths::getApplicationConfig()
            );

            /** @var ServiceManager $appServiceLocator */
            $appServiceManager = $this->getApplicationServiceLocator();

            $factory = new PluginMessageAbstractFactory();
            $appServiceManager->addAbstractFactory($factory);

            $appServiceManager->get(FooExtendAbstractSimpleMessage::class);
        } catch (ServiceNotCreatedException $e) {
            if (($parentException = $e->getPrevious()) && ($prev = $parentException->getPrevious())) {
                throw $prev;
            }
        }
    }


    /**
     * Проверка создания сообщения на основе релазации только интерфейса
     *
     */
    public function testCreateMessage()
    {
        $mockMessage = $this->getMock(MessageInterface::class);

        /** @noinspection PhpIncludeInspection */
        $this->setApplicationConfig(
            include TestPaths::getApplicationConfig()
        );

        /** @var EventBusMessagePluginManager $manager */
        $manager = $this->getApplicationServiceLocator()->get(EventBusMessagePluginManager::class);

        $actual = $manager->get(get_class($mockMessage));

        static::assertInstanceOf(MessageInterface::class, $actual);
    }
}
