<?php
/**
 * @link https://github.com/old-town/event-buss
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\PhpUnit\Test\EventBussManager;

use OldTown\EventBus\EventBussManager\EventBussPluginManager;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;



/**
 * Class EventBussPluginManagerTest
 *
 * @package OldTown\EventBus\PhpUnit\Test\EventBussManagerFacade
 */
class EventBussPluginManagerTest extends AbstractHttpControllerTestCase
{
    /**
     * Проверка создания не валидного EventBussManagerFacade
     *
     * @expectedException \OldTown\EventBus\EventBussManager\Exception\InvalidEventBussManagerException
     * @expectedExceptionMessage EventBussManager должен реализовывать OldTown\EventBus\EventBussManager\EventBussManagerInterface
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

        $eventBussPluginManager->setService('test', function () {
            return new \stdClass();
        });

        $eventBussPluginManager->get('test');
    }
}
