<?php
/**
 * @link https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\PhpUnit\Test\EventBusManager;

use OldTown\EventBus\EventBusManager\EventBusPluginManager;
use OldTown\EventBus\PhpUnit\TestData\TestPaths;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;



/**
 * Class EventBusPluginManagerTest
 *
 * @package OldTown\EventBus\PhpUnit\Test\EventBusManagerFacade
 */
class EventBusPluginManagerTest extends AbstractHttpControllerTestCase
{
    /**
     * Проверка создания не валидного EventBusManagerFacade
     *
     * @expectedException \OldTown\EventBus\EventBusManager\Exception\InvalidEventBusManagerException
     * @expectedExceptionMessage EventBusManager должен реализовывать OldTown\EventBus\EventBusManager\EventBusManagerInterface
     *
     * @throws \Zend\Stdlib\Exception\LogicException
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     */
    public function testNotValidPlugin()
    {
        /** @noinspection PhpIncludeInspection */
        $this->setApplicationConfig(
            include TestPaths::getApplicationConfig()
        );

        /** @var EventBusPluginManager $eventBusPluginManager */
        $eventBusPluginManager = $this->getApplicationServiceLocator()->get(EventBusPluginManager::class);

        $eventBusPluginManager->setService('test', function () {
            return new \stdClass();
        });

        $eventBusPluginManager->get('test');
    }
}
