<?php
/**
 * @link https://github.com/old-town/event-buss
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBuss\PhpUnitTest\EventBussManager;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;


/**
 * Class EventBussManagerAbstractFactory
 *
 * @package OldTown\EventBuss\PhpUnitTest\EventBussManager
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


    }


}
