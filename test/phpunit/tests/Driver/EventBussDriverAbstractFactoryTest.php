<?php
/**
 * @link https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\PhpUnit\Test\Driver;

use OldTown\EventBus\Driver\EventBussDriverInterface;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;


/**
 * Class EventBussDriverAbstractFactoryTest
 *
 * @package OldTown\EventBus\PhpUnit\Test\EventBussManagerFacade
 */
class EventBussDriverAbstractFactoryTest extends AbstractHttpControllerTestCase
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
        $driver = $this->getApplicationServiceLocator()->get('event_buss.driver.default');

        static::assertInstanceOf(EventBussDriverInterface::class, $driver);
    }

    /**
     * Проверка указания некорректного имени
     *
     * @expectedException \OldTown\EventBus\Driver\Exception\ErrorCreateEventBussDriverException
     * @expectedExceptionMessage Некорректный формат имени сервиса: eventbuss.driver.default.invalid
     *
     * @throws \Zend\Stdlib\Exception\LogicException
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     */
    public function testInvalidNameDriver()
    {
        $this->setApplicationConfig(
            include __DIR__ . '/../../_files/application.config.php'
        );
        $this->getApplicationServiceLocator()->get('event_buss.driver.default.invalid');
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
        $flag = $this->getApplicationServiceLocator()->has('event_buss.driver.not_exists');

        static::assertFalse($flag);
    }
}
