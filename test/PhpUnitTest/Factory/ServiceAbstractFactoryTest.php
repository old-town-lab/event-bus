<?php
/**
 * @link https://github.com/old-town/event-buss
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBuss\PhpUnitTest\Factory;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;


/**
 * Class ModuleTest
 *
 * @package OldTown\EventBuss\PhpUnitTest
 */
class ServiceAbstractFactoryTest extends AbstractHttpControllerTestCase
{
    /**
     * Проверка создания менеджера шины событий через абстрактную фабрику
     *
     */
    public function testGetDefaultEventBussManager()
    {
        $this->setApplicationConfig(
            include __DIR__ . '/../../_files/application.config.php'
        );
        $this->getApplicationServiceLocator()->get('event_buss.manager.default');
    }
}
