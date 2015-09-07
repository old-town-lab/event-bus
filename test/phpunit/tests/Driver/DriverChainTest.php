<?php
/**
 * @link    https://github.com/old-town/event-buss
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\PhpUnit\Test\Driver;

use OldTown\EventBus\Driver\DriverConfig;
use OldTown\EventBus\Driver\EventBussDriverInterface;
use OldTown\EventBus\Driver\EventBussDriverPluginManager;
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
     * @expectedException \OldTown\EventBus\Driver\Exception\ErrorCreateEventBussDriverException
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

        /** @var PHPUnit_Framework_MockObject_MockObject|\OldTown\EventBus\Driver\EventBussDriverPluginManager $eventBussDriverPluginManager */
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

    /**
     * Тестирование иницализации шины. Создаем два мок объекта, имитирующих драйвера. Ожидаем что у них по одному разу
     * будут вызванны методы initEventBuss.
     *
     */
    public function testInitEventBuss()
    {
        $this->setApplicationConfig(
            include __DIR__ . '/../../_files/application.config.php'
        );
        /** @var EventBussDriverPluginManager $eventBussDriverPluginManager */
        $eventBussDriverPluginManager = $this->getApplicationServiceLocator()->get(EventBussDriverPluginManager::class);

        /** @var DriverChain $driverChain */
        $driverChain = $eventBussDriverPluginManager->get('chain');

        $methods = get_class_methods(EventBussDriverInterface::class);
        for ($i = 0; $i < 2; $i++) {
            /** @var EventBussDriverInterface|PHPUnit_Framework_MockObject_MockObject $driver */
            $driver = static::getMock(EventBussDriverInterface::class, $methods);
            foreach ($methods as $method) {
                if ('initEventBuss' === $method) {
                    $driver->expects(static::once())->method($method);
                } else {
                    $driver->expects(static::any())->method($method);
                }
            }
            $driverChain->addDriver($driver);
        }

        $driverChain->initEventBuss();
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
        /** @var EventBussDriverPluginManager $eventBussDriverPluginManager */
        $eventBussDriverPluginManager = $this->getApplicationServiceLocator()->get(EventBussDriverPluginManager::class);

        /** @var DriverChain $driverChain */
        $driverChain = $eventBussDriverPluginManager->get('chain');

        $message = new Foo();
        $driverChain->trigger('test_event', $message);
    }
}
