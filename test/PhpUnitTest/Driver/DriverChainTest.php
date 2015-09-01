<?php
/**
 * @link    https://github.com/old-town/event-buss
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBuss\PhpUnitTest\Driver;

use OldTown\EventBuss\Driver\DriverConfig;
use OldTown\EventBuss\Driver\EventBussDriverPluginManager;
use OldTown\EventBuss\Driver\RabbitMqDriver;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use OldTown\EventBuss\Driver\DriverChain;
use PHPUnit_Framework_MockObject_MockObject;

/**
 * Class DriverChainTest
 *
 * @package OldTown\EventBuss\PhpUnitTest\Driver
 */
class DriverChainTest extends AbstractHttpControllerTestCase
{
    /**
     * Создание драйвера реализующего агрегацию драйверов
     *
     */
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


    /**
     * Ошибка при создание драйверов на основе конфига
     *
     * @expectedException \OldTown\EventBuss\Driver\Exception\ErrorCreateEventBussDriverException
     */
    public function testErrorBuildDriversFromConfig()
    {
        $this->setApplicationConfig(
            include __DIR__ . '/../../_files/application.config.php'
        );
        /** @var EventBussDriverPluginManager $eventBussDriverPluginManager */
        $eventBussDriverPluginManager = $this->getApplicationServiceLocator()->get(EventBussDriverPluginManager::class);

        /** @var DriverChain $driverChain */
        $driverChain = $eventBussDriverPluginManager->get('chain');

        /** @var PHPUnit_Framework_MockObject_MockObject|\OldTown\EventBuss\Driver\EventBussDriverPluginManager $eventBussDriverPluginManager */
        $eventBussDriverPluginManager = $this->getMock(EventBussDriverPluginManager::class, [
           'get'
        ]);
        $e = new \Exception();
        $eventBussDriverPluginManager->expects(static::once())->method('get')->will(static::throwException($e));

        $driverChain->setEventBussDriverPluginManager($eventBussDriverPluginManager);

        $driverChain->setOptions(
            [
                DriverConfig::DRIVERS =>
                    [
                        [
                            DriverConfig::PLUGIN_NAME => RabbitMqDriver::class,
                            DriverConfig::CONNECTION => 'default'
                        ]
                    ]
            ]
        );
    }

    /**
     * Получение драйвера из цепочки
     *
     */
    public function testGetDriver()
    {
        $this->setApplicationConfig(
            include __DIR__ . '/../../_files/application.config.php'
        );
        /** @var EventBussDriverPluginManager $eventBussDriverPluginManager */
        $eventBussDriverPluginManager = $this->getApplicationServiceLocator()->get(EventBussDriverPluginManager::class);

        /** @var DriverChain $driverChain */
        $driverChain = $eventBussDriverPluginManager->get('chain', [
            'pluginName' => DriverChain::class,
            'drivers'    => [
                'amqp' => [
                    'pluginName' => RabbitMqDriver::class,
                    'connection' => 'default'
                ]
            ]
        ]);

        $drivers = $driverChain->getDrivers();

        static::assertEquals(1, $drivers->count());

        $drivers->rewind();
        static::assertInstanceOf(RabbitMqDriver::class, $drivers->current());
    }
}
