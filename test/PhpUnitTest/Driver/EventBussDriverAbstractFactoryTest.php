<?php
/**
 * @link https://github.com/old-town/event-buss
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBuss\PhpUnitTest\Driver;

use OldTown\EventBuss\Driver\EventBussDriverInterface;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;


/**
 * Class EventBussDriverAbstractFactoryTest
 *
 * @package OldTown\EventBuss\PhpUnitTest\EventBussManager
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
     * @expectedException \OldTown\EventBuss\Driver\Exception\ErrorCreateEventBussDriverException
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
