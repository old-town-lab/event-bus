<?php
/**
 * @link https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\PhpUnit\Test\EventBusManager;

use OldTown\EventBus\EventBusManager\EventBusManagerInterface;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use OldTown\EventBus\EventBusManager\EventBusPluginManager;
use OldTown\EventBus\EventBusManager\EventBusManagerFacade;
use OldTown\EventBus\Driver\EventBusDriverInterface;

/**
 * Class EventBusManagerTest
 *
 * @package OldTown\EventBus\PhpUnit\Test\EventBusManagerFacade
 */
class EventBusManagerTest extends AbstractHttpControllerTestCase
{
    /**
     * Создаем стандартного EventBusManagerFacade
     *
     */
    public function testCreateEventBusDefaultManager()
    {
        $this->setApplicationConfig(
            include __DIR__ . '/../../_files/application.config.php'
        );
        /** @var EventBusPluginManager $eventBusPluginManager */
        $eventBusPluginManager = $this->getApplicationServiceLocator()->get('eventBusPluginManager');

        static::assertInstanceOf(EventBusPluginManager::class, $eventBusPluginManager);

        $config = [
            'driver' => 'default'
        ];
        /** @var EventBusManagerInterface $eventBusManager */
        $eventBusManager = $eventBusPluginManager->get('default', $config);

        static::assertInstanceOf(EventBusManagerFacade::class, $eventBusManager);

        static::assertInstanceOf(EventBusDriverInterface::class, $eventBusManager->getDriver());
    }
}
