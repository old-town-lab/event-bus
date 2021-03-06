<?php
/**
 * @link https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\PhpUnit\Test\Driver;

use OldTown\EventBus\Driver\EventBusDriverPluginManager;
use OldTown\EventBus\PhpUnit\TestData\TestPaths;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;



/**
 * Class EventBusDriverPluginManagerTest
 *
 * @package OldTown\EventBus\PhpUnit\Test\Driver
 */
class EventBusDriverPluginManagerTest extends AbstractHttpControllerTestCase
{
    /**
     * Проверка создания не валидного драйвера
     *
     * @expectedException \OldTown\EventBus\Driver\Exception\InvalidEventBusDriverException
     * @expectedExceptionMessage EventBusDriver должен реализовывать OldTown\EventBus\Driver\EventBusDriverInterface
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

        /** @var EventBusDriverPluginManager $eventBusDriverPluginManager */
        $eventBusDriverPluginManager = $this->getApplicationServiceLocator()->get(EventBusDriverPluginManager::class);

        $eventBusDriverPluginManager->setService('test', function () {
            return new \stdClass();
        });

        $eventBusDriverPluginManager->get('test');
    }
}
