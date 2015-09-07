<?php
/**
 * @link https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\PhpUnit\Test\Driver;

use OldTown\EventBus\Driver\EventBusDriverInterface;
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
        $this->setApplicationConfig(
            include __DIR__ . '/../../_files/application.config.php'
        );
        $driver = $this->getApplicationServiceLocator()->get('event_bus.driver.default');

        static::assertInstanceOf(EventBusDriverInterface::class, $driver);
    }

    /**
     * Проверка указания некорректного имени
     *
     * @expectedException \OldTown\EventBus\Driver\Exception\ErrorCreateEventBusDriverException
     * @expectedExceptionMessage Некорректный формат имени сервиса: eventbus.driver.default.invalid
     *
     * @throws \Zend\Stdlib\Exception\LogicException
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     */
    public function testInvalidNameDriver()
    {
        $this->setApplicationConfig(
            include __DIR__ . '/../../_files/application.config.php'
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
        $this->setApplicationConfig(
            include __DIR__ . '/../../_files/application.config.php'
        );
        $flag = $this->getApplicationServiceLocator()->has('event_bus.driver.not_exists');

        static::assertFalse($flag);
    }
}
