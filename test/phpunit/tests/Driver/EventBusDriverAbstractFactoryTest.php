<?php
/**
 * @link https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\PhpUnit\Test\Driver;

use OldTown\EventBus\Driver\EventBusDriverInterface;
use OldTown\EventBus\PhpUnit\TestData\TestPaths;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;


/**
 * Class EventBusDriverAbstractFactoryTest
 *
 * @package OldTown\EventBus\PhpUnit\Test\EventBusManagerFacade
 */
class EventBusDriverAbstractFactoryTest extends AbstractHttpControllerTestCase
{
    /**
     * Проверка создания драйвера для  шины событий через абстрактную фабрику
     *
     * @throws \Zend\Stdlib\Exception\LogicException
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     */
    public function testGetDefaultDriver()
    {
        /** @noinspection PhpIncludeInspection */
        $this->setApplicationConfig(
            include TestPaths::getApplicationConfig()
        );
        $driver = $this->getApplicationServiceLocator()->get('event_bus.driver.default');

        static::assertInstanceOf(EventBusDriverInterface::class, $driver);
    }

    /**
     * Проверка указания некорректного имени
     *
     * @expectedException \OldTown\EventBus\Driver\Exception\ErrorCreateEventBusDriverException
     * @expectedExceptionMessage Некорректный формат имени сервиса: event_bus.driver.default.invalid
     *
     * @throws \Zend\Stdlib\Exception\LogicException
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     */
    public function testInvalidNameDriver()
    {
        /** @noinspection PhpIncludeInspection */
        $this->setApplicationConfig(
            include TestPaths::getApplicationConfig()
        );
        $this->getApplicationServiceLocator()->get('event_bus.driver.default.invalid');
    }


    /**
     * Проверка указания несуществующего имени
     *
     * @throws \Zend\Stdlib\Exception\LogicException
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     */
    public function testNotExistsDriver()
    {
        /** @noinspection PhpIncludeInspection */
        $this->setApplicationConfig(
            include TestPaths::getApplicationConfig()
        );
        $flag = $this->getApplicationServiceLocator()->has('event_bus.driver.not_exists');

        static::assertFalse($flag);
    }
}
