<?php
/**
 * @link https://github.com/old-town/event-buss
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\PhpUnit\Test\Driver;

use OldTown\EventBus\Driver\EventBussDriverPluginManager;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;



/**
 * Class EventBussDriverPluginManagerTest
 *
 * @package OldTown\EventBus\PhpUnit\Test\Driver
 */
class EventBussDriverPluginManagerTest extends AbstractHttpControllerTestCase
{
    /**
     * Проверка создания не валидного драйвера
     *
     * @expectedException \OldTown\EventBus\Driver\Exception\InvalidEventBussDriverException
     * @expectedExceptionMessage EventBussDriver должен реализовывать OldTown\EventBus\Driver\EventBussDriverInterface
     *
     * @throws \Zend\Stdlib\Exception\LogicException
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     */
    public function testNotValidPlugin()
    {
        $this->setApplicationConfig(
            include __DIR__ . '/../../_files/application.config.php'
        );

        /** @var EventBussDriverPluginManager $eventBussDriverPluginManager */
        $eventBussDriverPluginManager = $this->getApplicationServiceLocator()->get(EventBussDriverPluginManager::class);

        $eventBussDriverPluginManager->setService('test', function () {
            return new \stdClass();
        });

        $eventBussDriverPluginManager->get('test');
    }
}
