<?php
/**
 * @link https://github.com/old-town/event-buss
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBuss\PhpUnitTest\EventBussManager;

use OldTown\EventBuss\EventBussManager\EventBussManagerInterface;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use OldTown\EventBuss\EventBussManager\EventBussPluginManager;
use OldTown\EventBuss\EventBussManager\EventBussManager;
use OldTown\EventBuss\Driver\EventBussDriverInterface;

/**
 * Class EventBussManagerTest
 *
 * @package OldTown\EventBuss\PhpUnitTest\EventBussManager
 */
class EventBussManagerTest extends AbstractHttpControllerTestCase
{
    /**
     * Создаем стандартного EventBussManager
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

        static::assertInstanceOf(EventBussManager::class, $eventBussManager);

        static::assertInstanceOf(EventBussDriverInterface::class, $eventBussManager->getDriver());
    }
}
