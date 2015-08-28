<?php
/**
 * @link https://github.com/old-town/event-buss
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBuss\PhpUnitTest\EventBussManager;

use OldTown\EventBuss\EventBussManager\EventBussPluginManager;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use OldTown\EventBuss\EventBussManager\EventBussManager;


/**
 * Class EventBussPluginManagerTest
 *
 * @package OldTown\EventBuss\PhpUnitTest\EventBussManager
 */
class EventBussPluginManagerTest extends AbstractHttpControllerTestCase
{
    /**
     * Проверка создания менеджера шины событий через абстрактную фабрику
     *
     * @expectedException \OldTown\EventBuss\EventBussManager\Exception\InvalidEventBussManagerException
     * @expectedExceptionMessage EventBussManager должен реализовывать OldTown\EventBuss\EventBussManager\EventBussManagerInterface
     *
     * @throws \Zend\Stdlib\Exception\LogicException
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     */
    public function testNotValidPlugin()
    {
        $this->setApplicationConfig(
            include __DIR__ . '/../../_files/application.config.php'
        );

        /** @var EventBussPluginManager $eventBussPluginManager */
        $eventBussPluginManager = $this->getApplicationServiceLocator()->get(EventBussPluginManager::class);

        $eventBussPluginManager->setService('test', function() {
            return new \stdClass();
        });

        $eventBussPluginManager->get('test');
    }
}
