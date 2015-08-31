<?php
/**
 * @link    https://github.com/old-town/event-buss
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBuss\PhpUnitTest\Driver;

use OldTown\EventBuss\Driver\EventBussDriverPluginManager;
use OldTown\EventBuss\Driver\RabbitMqDriver;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use OldTown\EventBuss\Driver\DriverChain;

/**
 * Class DriverChainTest
 *
 * @package OldTown\EventBuss\PhpUnitTest\Driver
 */
class DriverChainTest extends AbstractHttpControllerTestCase
{
    public function testCreateDriverChain()
    {
        $this->setApplicationConfig(
            include __DIR__ . '/../../_files/application.config.php'
        );
        /** @var EventBussDriverPluginManager $eventBussDriverPluginManager */
        $eventBussDriverPluginManager = $this->getApplicationServiceLocator()->get(EventBussDriverPluginManager::class);

        $driverChain = $eventBussDriverPluginManager->get('chain', [
            'pluginName' => DriverChain::class,
            'drivers'    => [
                'amqp' => [
                    'pluginName' => RabbitMqDriver::class,
                    'connection' => 'default'
                ]
            ]
        ]);


        static::assertInstanceOf(DriverChain::class, $driverChain);
    }
}
