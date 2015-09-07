<?php
/**
 * @link    https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\PhpUnit\Test\Driver;

use OldTown\EventBus\Driver\DriverConfig;
use OldTown\EventBus\Driver\EventBusDriverInterface;
use OldTown\EventBus\Driver\EventBusDriverPluginManager;
use OldTown\EventBus\Driver\RabbitMqDriver;
use OldTown\EventBus\PhpUnit\TestData\Messages\Foo;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use OldTown\EventBus\Driver\DriverChain;
use PHPUnit_Framework_MockObject_MockObject;

/**
 * Class DriverChainTest
 *
 * @package OldTown\EventBus\PhpUnit\Test\Driver
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
        /** @var EventBusDriverPluginManager $eventBusDriverPluginManager */
        $eventBusDriverPluginManager = $this->getApplicationServiceLocator()->get(EventBusDriverPluginManager::class);

        $driverChain = $eventBusDriverPluginManager->get('chain', [
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
     * @expectedException \OldTown\EventBus\Driver\Exception\ErrorCreateEventBusDriverException
     */
    public function testErrorBuildDriversFromConfig()
    {
        $this->setApplicationConfig(
            include __DIR__ . '/../../_files/application.config.php'
        );
        /** @var EventBusDriverPluginManager $eventBusDriverPluginManager */
        $eventBusDriverPluginManager = $this->getApplicationServiceLocator()->get(EventBusDriverPluginManager::class);

        /** @var DriverChain $driverChain */
        $driverChain = $eventBusDriverPluginManager->get('chain');

        /** @var PHPUnit_Framework_MockObject_MockObject|\OldTown\EventBus\Driver\EventBusDriverPluginManager $eventBusDriverPluginManager */
        $eventBusDriverPluginManager = $this->getMock(EventBusDriverPluginManager::class, [
           'get'
        ]);
        $e = new \Exception();
        $eventBusDriverPluginManager->expects(static::once())->method('get')->will(static::throwException($e));

        $driverChain->setEventBusDriverPluginManager($eventBusDriverPluginManager);

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
        /** @var EventBusDriverPluginManager $eventBusDriverPluginManager */
        $eventBusDriverPluginManager = $this->getApplicationServiceLocator()->get(EventBusDriverPluginManager::class);

        /** @var DriverChain $driverChain */
        $driverChain = $eventBusDriverPluginManager->get('chain', [
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

    /**
     * Тестирование иницализации шины. Создаем два мок объекта, имитирующих драйвера. Ожидаем что у них по одному разу
     * будут вызванны методы initEventBus.
     *
     */
    public function testInitEventBus()
    {
        $this->setApplicationConfig(
            include __DIR__ . '/../../_files/application.config.php'
        );
        /** @var EventBusDriverPluginManager $eventBusDriverPluginManager */
        $eventBusDriverPluginManager = $this->getApplicationServiceLocator()->get(EventBusDriverPluginManager::class);

        /** @var DriverChain $driverChain */
        $driverChain = $eventBusDriverPluginManager->get('chain');

        $methods = get_class_methods(EventBusDriverInterface::class);
        for ($i = 0; $i < 2; $i++) {
            /** @var EventBusDriverInterface|PHPUnit_Framework_MockObject_MockObject $driver */
            $driver = static::getMock(EventBusDriverInterface::class, $methods);
            foreach ($methods as $method) {
                if ('initEventBus' === $method) {
                    $driver->expects(static::once())->method($method);
                } else {
                    $driver->expects(static::any())->method($method);
                }
            }
            $driverChain->addDriver($driver);
        }

        $driverChain->initEventBus();
    }

    /**
     * Тестирование бросание события
     *
     */
    public function testTrigger()
    {
        $this->setApplicationConfig(
            include __DIR__ . '/../../_files/application.config.php'
        );
        /** @var EventBusDriverPluginManager $eventBusDriverPluginManager */
        $eventBusDriverPluginManager = $this->getApplicationServiceLocator()->get(EventBusDriverPluginManager::class);

        /** @var DriverChain $driverChain */
        $driverChain = $eventBusDriverPluginManager->get('chain');

        $message = new Foo();
        $driverChain->trigger('test_event', $message);
    }
}
