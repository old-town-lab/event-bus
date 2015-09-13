<?php
/**
 * @link https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\PhpUnit\Test\EventBusManager;

use OldTown\EventBus\PhpUnit\TestData\TestPaths;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use OldTown\EventBus\EventBusManager\EventBusManagerFacade;


/**
 * Class EventBusManagerAbstractFactory
 *
 * @package OldTown\EventBus\PhpUnit\Test\EventBusManagerFacade
 */
class EventBusManagerAbstractFactoryTest extends AbstractHttpControllerTestCase
{
    /**
     * Проверка создания менеджера шины событий через абстрактную фабрику
     *
     * @throws \Zend\Stdlib\Exception\LogicException
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     */
    public function testGetDefaultEventBusManager()
    {
        /** @noinspection PhpIncludeInspection */
        $this->setApplicationConfig(
            include TestPaths::getApplicationConfig()
        );
        $eventBusManager = $this->getApplicationServiceLocator()->get('event_bus.manager.default');

        static::assertInstanceOf(EventBusManagerFacade::class, $eventBusManager);
    }

    /**
     * Проверка указания некорректного имени фабрики
     *
     * @expectedException \OldTown\EventBus\EventBusManager\Exception\ErrorCreateEventBusManagerException
     * @expectedExceptionMessage Некорректный формат имени сервиса: event_bus.manager.default.invalid
     *
     * @throws \Zend\Stdlib\Exception\LogicException
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     */
    public function testInvalidNameEventBusManager()
    {
        /** @noinspection PhpIncludeInspection */
        $this->setApplicationConfig(
            include TestPaths::getApplicationConfig()
        );
        $this->getApplicationServiceLocator()->get('event_bus.manager.default.invalid');
    }


    /**
     * Проверка указания несуществующего имени фабрики
     *
     * @throws \Zend\Stdlib\Exception\LogicException
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     */
    public function testNotExistsEventNameBusManager()
    {
        /** @noinspection PhpIncludeInspection */
        $this->setApplicationConfig(
            include TestPaths::getApplicationConfig()
        );
        $flag = $this->getApplicationServiceLocator()->has('event_bus.manager.not_exists');

        static::assertFalse($flag);
    }
}
