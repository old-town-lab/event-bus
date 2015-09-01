<?php
/**
 * @link https://github.com/old-town/event-buss
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBuss\PhpUnitTest\EventBussManager;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use OldTown\EventBuss\EventBussManager\EventBussManagerFacade;


/**
 * Class EventBussManagerAbstractFactory
 *
 * @package OldTown\EventBuss\PhpUnitTest\EventBussManagerFacade
 */
class EventBussManagerAbstractFactoryTest extends AbstractHttpControllerTestCase
{
    /**
     * Проверка создания менеджера шины событий через абстрактную фабрику
     *
     * @throws \Zend\Stdlib\Exception\LogicException
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     */
    public function testGetDefaultEventBussManager()
    {
        $this->setApplicationConfig(
            include __DIR__ . '/../../_files/application.config.php'
        );
        $eventBussManager = $this->getApplicationServiceLocator()->get('event_buss.manager.default');

        static::assertInstanceOf(EventBussManagerFacade::class, $eventBussManager);
    }

    /**
     * Проверка указания некорректного имени фабрики
     *
     * @expectedException \OldTown\EventBuss\EventBussManager\Exception\ErrorCreateEventBussManagerException
     * @expectedExceptionMessage eventbuss.manager.default.invalid
     *
     * @throws \Zend\Stdlib\Exception\LogicException
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     */
    public function testInvalidNameEventBussManager()
    {
        $this->setApplicationConfig(
            include __DIR__ . '/../../_files/application.config.php'
        );
        $this->getApplicationServiceLocator()->get('event_buss.manager.default.invalid');
    }


    /**
     * Проверка указания несуществующего имени фабрики
     *
     * @throws \Zend\Stdlib\Exception\LogicException
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     */
    public function testNotExistsEventNameBussManager()
    {
        $this->setApplicationConfig(
            include __DIR__ . '/../../_files/application.config.php'
        );
        $flag = $this->getApplicationServiceLocator()->has('event_buss.manager.not_exists');

        static::assertFalse($flag);
    }
}
