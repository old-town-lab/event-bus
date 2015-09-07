<?php
/**
 * @link https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\PhpUnit\Test\EventBussManager;

use OldTown\EventBus\EventBussManager\EventBussManagerInterface;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use OldTown\EventBus\EventBussManager\EventBussPluginManager;
use OldTown\EventBus\EventBussManager\EventBussManagerFacade;
use OldTown\EventBus\Driver\EventBussDriverInterface;

/**
 * Class EventBussManagerTest
 *
 * @package OldTown\EventBus\PhpUnit\Test\EventBussManagerFacade
 */
class EventBussManagerTest extends AbstractHttpControllerTestCase
{
    /**
     * Создаем стандартного EventBussManagerFacade
     *
     */
    public function testCreateEventBussDefaultManager()
    {
        $this->setApplicationConfig(
            include __DIR__ . '/../../_files/application.config.php'
        );
        /** @var EventBussPluginManager $eventBussPluginManager */
        $eventBussPluginManager = $this->getApplicationServiceLocator()->get('eventBussPluginManager');

        static::assertInstanceOf(EventBussPluginManager::class, $eventBussPluginManager);

        $config = [
            'driver' => 'default'
        ];
        /** @var EventBussManagerInterface $eventBussManager */
        $eventBussManager = $eventBussPluginManager->get('default', $config);

        static::assertInstanceOf(EventBussManagerFacade::class, $eventBussManager);

        static::assertInstanceOf(EventBussDriverInterface::class, $eventBussManager->getDriver());
    }
}
