<?php
/**
 * @link    https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\PhpUnit\Test\Driver;

use OldTown\EventBus\Driver\EventBusDriverInterface;
use OldTown\EventBus\Driver\MetadataReaderInterface;
use OldTown\EventBus\Driver\RabbitMqDriver;
use OldTown\EventBus\EventBusManager\EventBusManagerFacade;
use OldTown\EventBus\Module;
use OldTown\EventBus\PhpUnit\TestData\Messages\Foo;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\ArrayUtils;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use OldTown\EventBus\Driver\RabbitMqDriver\Adapter\AmqpPhpExtension;
use OldTown\EventBus\Driver\RabbitMqDriver\Adapter\AdapterInterface;
use OldTown\EventBus\PhpUnit\RabbitMqTestUtils\RabbitMqTestCaseTrait;
use OldTown\EventBus\PhpUnit\RabbitMqTestUtils\RabbitMqTestCaseInterface;
use OldTown\EventBus\Driver\RabbitMqDriver\MetadataReader\Metadata;


/**
 * Class DriverChainTest
 *
 * @package OldTown\EventBus\PhpUnit\Test\Driver
 */
class _RabbitMqDriverTest extends AbstractHttpControllerTestCase implements RabbitMqTestCaseInterface
{
    use RabbitMqTestCaseTrait;

    /**
     * Получение имени адаптера используемого для работы с сервером очередей
     *
     */
    public function testGetAdapterName()
    {
        $this->setApplicationConfig(
            include __DIR__ . '/../../_files/application.config.php'
        );

        $appServiceManager = $this->getApplicationServiceLocator();
        /** @var Module $module */
        $module = $appServiceManager->get(Module::class);

        $managerConfig = ArrayUtils::merge($module->getModuleOptions()->getEventBusManager(), [
            'example' => [
                'driver' => 'example'
            ]
        ]);
        $module->getModuleOptions()->setEventBusManager($managerConfig);

        $driverConfig = ArrayUtils::merge($module->getModuleOptions()->getDriver(), [
            'example' => [
                'pluginName' => RabbitMqDriver::class,
            ]
        ]);
        $module->getModuleOptions()->setDriver($driverConfig);

        /** @var EventBusManagerFacade $eventBusManager */
        $eventBusManager = $appServiceManager->get('event_bus.manager.example');

        /** @var RabbitMqDriver $driver */
        $driver = $eventBusManager->getDriver();

        $actualAdapterName = $driver->getAdapterName();

        static::assertEquals($actualAdapterName, AmqpPhpExtension::class);
    }


    /**
     * Получение имени адаптера используемого для работы с сервером очередей. Имя адаптера задается через конфиг.
     * Указан несуществующий класс
     *
     * @expectedException \OldTown\EventBus\Driver\Exception\InvalidAdapterNameException
     * @expectedExceptionMessage Отсутствует класс адаптера invalid-adapter
     */
    public function testGetInvalidAdapterNameFromExtraOptions()
    {
        $this->setApplicationConfig(
            include __DIR__ . '/../../_files/application.config.php'
        );

        $appServiceManager = $this->getApplicationServiceLocator();
        /** @var Module $module */
        $module = $appServiceManager->get(Module::class);

        $managerConfig = ArrayUtils::merge($module->getModuleOptions()->getEventBusManager(), [
            'example' => [
                'driver' => 'example'
            ]
        ]);
        $module->getModuleOptions()->setEventBusManager($managerConfig);

        $driverConfig = ArrayUtils::merge($module->getModuleOptions()->getDriver(), [
            'example' => [
                'pluginName' => RabbitMqDriver::class,
                'adapter' => 'invalid-adapter'
            ]
        ]);
        $module->getModuleOptions()->setDriver($driverConfig);

        /** @var EventBusManagerFacade $eventBusManager */
        $eventBusManager = $appServiceManager->get('event_bus.manager.example');

        /** @var RabbitMqDriver $driver */
        $driver = $eventBusManager->getDriver();

        $driver->getAdapterName();
    }


    /**
     * Получение имени адаптера используемого для работы с сервером очередей. Имя адаптера задается через конфиг.
     * Указан класс который не реализует интерфейс адаптера
     *
     * @expectedException \OldTown\EventBus\Driver\Exception\InvalidAdapterNameException
     * @expectedExceptionMessage Адаптер должен реализовывать OldTown\EventBus\Driver\RabbitMqDriver\Adapter\AdapterInterface
     */
    public function testGetInvalidAdapterFromExtraOptions()
    {
        $this->setApplicationConfig(
            include __DIR__ . '/../../_files/application.config.php'
        );

        $appServiceManager = $this->getApplicationServiceLocator();
        /** @var Module $module */
        $module = $appServiceManager->get(Module::class);

        $managerConfig = ArrayUtils::merge($module->getModuleOptions()->getEventBusManager(), [
            'example' => [
                'driver' => 'example'
            ]
        ]);
        $module->getModuleOptions()->setEventBusManager($managerConfig);

        $driverConfig = ArrayUtils::merge($module->getModuleOptions()->getDriver(), [
            'example' => [
                'pluginName' => RabbitMqDriver::class,
                'adapter' => \stdClass::class
            ]
        ]);
        $module->getModuleOptions()->setDriver($driverConfig);

        /** @var EventBusManagerFacade $eventBusManager */
        $eventBusManager = $appServiceManager->get('event_bus.manager.example');

        /** @var RabbitMqDriver $driver */
        $driver = $eventBusManager->getDriver();

        $driver->getAdapterName();
    }


    /**
     * Получение имени адаптера используемого для работы с сервером очередей. Имя адаптера задается через конфиг.
     *
     */
    public function testGetAdapterFromExtraOptions()
    {
        $this->setApplicationConfig(
            include __DIR__ . '/../../_files/application.config.php'
        );

        $appServiceManager = $this->getApplicationServiceLocator();
        /** @var Module $module */
        $module = $appServiceManager->get(Module::class);

        $managerConfig = ArrayUtils::merge($module->getModuleOptions()->getEventBusManager(), [
            'example' => [
                'driver' => 'example'
            ]
        ]);
        $module->getModuleOptions()->setEventBusManager($managerConfig);


        $adapter = $this->getMock(AdapterInterface::class);
        $expectedAdapterName = get_class($adapter);

        $driverConfig = ArrayUtils::merge($module->getModuleOptions()->getDriver(), [
            'example' => [
                'pluginName' => RabbitMqDriver::class,
                'adapter' => $expectedAdapterName
            ]
        ]);
        $module->getModuleOptions()->setDriver($driverConfig);

        /** @var EventBusManagerFacade $eventBusManager */
        $eventBusManager = $appServiceManager->get('event_bus.manager.example');

        /** @var RabbitMqDriver $driver */
        $driver = $eventBusManager->getDriver();

        $actualAdapterName = $driver->getAdapterName();

        static::assertEquals($expectedAdapterName, $actualAdapterName);
    }

    /**
     * Получение имени адаптера используемого для работы с сервером очередей. Имя адаптера задается через конфиг.
     *
     */
    public function testLocalCacheGetAdapterName()
    {
        $this->setApplicationConfig(
            include __DIR__ . '/../../_files/application.config.php'
        );

        $appServiceManager = $this->getApplicationServiceLocator();
        /** @var Module $module */
        $module = $appServiceManager->get(Module::class);

        $managerConfig = ArrayUtils::merge($module->getModuleOptions()->getEventBusManager(), [
            'example' => [
                'driver' => 'example'
            ]
        ]);
        $module->getModuleOptions()->setEventBusManager($managerConfig);


        $adapter = $this->getMock(AdapterInterface::class);
        $adapterName = get_class($adapter);

        $driverConfig = ArrayUtils::merge($module->getModuleOptions()->getDriver(), [
            'example' => [
                'pluginName' => RabbitMqDriver::class,
                'adapter' => $adapterName
            ]
        ]);
        $module->getModuleOptions()->setDriver($driverConfig);

        /** @var EventBusManagerFacade $eventBusManager */
        $eventBusManager = $appServiceManager->get('event_bus.manager.example');

        /** @var RabbitMqDriver $driver */
        $driver = $eventBusManager->getDriver();

        $expectedAdapterName = $driver->getAdapterName();
        $actualAdapterName = $driver->getAdapterName();

        static::assertEquals($expectedAdapterName, $actualAdapterName);
    }

    /**
     * Получение имени адаптера используемого для работы с сервером очередей. Имя адаптера задается через конфиг.
     *
     */
    public function testGetAdapter()
    {
        $this->setApplicationConfig(
            include __DIR__ . '/../../_files/application.config.php'
        );

        $appServiceManager = $this->getApplicationServiceLocator();
        /** @var Module $module */
        $module = $appServiceManager->get(Module::class);

        $managerConfig = ArrayUtils::merge($module->getModuleOptions()->getEventBusManager(), [
            'example' => [
                'driver' => 'example'
            ]
        ]);
        $module->getModuleOptions()->setEventBusManager($managerConfig);


        $adapterMock = $this->getMock(AdapterInterface::class);
        $adapterName = get_class($adapterMock);

        $driverConfig = ArrayUtils::merge($module->getModuleOptions()->getDriver(), [
            'example' => [
                'pluginName' => RabbitMqDriver::class,
                'adapter' => $adapterName
            ]
        ]);
        $module->getModuleOptions()->setDriver($driverConfig);

        /** @var EventBusManagerFacade $eventBusManager */
        $eventBusManager = $appServiceManager->get('event_bus.manager.example');

        /** @var RabbitMqDriver $driver */
        $driver = $eventBusManager->getDriver();

        $adapter = $driver->getAdapter();

        static::assertInstanceOf(AdapterInterface::class, $adapter);

        static::assertFalse($adapter === $adapterMock);

        /**
         * Проверяем локальное кеширование
         */
        static::assertTrue($adapter === $driver->getAdapter());
    }

    /**
     * Проверка поведения драйвера, когда не установлено расширение для работы с сервером очередей
     *
     * @expectedException \OldTown\EventBus\Driver\RabbitMqDriver\Adapter\Exception\AmqpPhpExtensionNotInstalledException
     * @expectedExceptionMessage Для работы драйвера необходимо php расширение amqp_extension_not_found
     */
    public function testAmqpPhpExtensionNotFound()
    {
        $this->setApplicationConfig(
            include __DIR__ . '/../../_files/application.config.php'
        );

        $r = new \ReflectionClass(AmqpPhpExtension::class);
        $property = $r->getProperty('amqpPhpExtensionName');
        $property->setAccessible(true);
        $originalValue = $property->getValue();
        $property->setValue(null, 'amqp_extension_not_found');
        try {
            $r->newInstance();
        } catch (\Exception $e) {
            throw $e;
        } finally {
            $property->setValue(null, $originalValue);
        }
    }


    /**
     * Тестирование инициализация обменников и очередей
     *
     *
     *  @group RabbitMqTest
     */
    public function testInitEventBus()
    {
        $this->setApplicationConfig(
            include __DIR__ . '/../../_files/application.config.php'
        );

        $appServiceManager = $this->getApplicationServiceLocator();


        $connectionConfig = $this->getRabbitMqConnectionForTest();


        $this->buildEventBusManager($appServiceManager, [
            'event_bus_manager' => [
                'example' => [
                    'driver' => 'example'
                ]
            ],
            'connection' => [
                'example' => [
                    'params' => $connectionConfig
                ]
            ],
            'driver' => [
                'example' => [
                    'pluginName' => RabbitMqDriver::class,
                    'connection' => 'example',
                    'paths' => [
                        __DIR__ . '/../../_files/Messages'
                    ]
                ]
            ]
        ]);

        /** @var EventBusManagerFacade $eventBusManager */
        $eventBusManager = $appServiceManager->get('event_bus.manager.example');

        /** @var EventBusDriverInterface|MetadataReaderInterface $driver */
        $driver = $eventBusManager->getDriver();
        $driver->initEventBus();

        $classes = $driver->getMetadataReader()->getAllClassNames();
        foreach ($classes as $class) {
            /** @var Metadata $metadata */
            $metadata = $driver->getMetadataReader()->loadMetadataForClass($class);

            $actualExchange = $this->getRabbitMqTestManager()->getExchange($metadata->getExchangeName());

            $expected = [
                'name' => $metadata->getExchangeName(),
                'type' => 'topic'
            ];
            static::assertEquals($expected, $actualExchange);


            $actualQueue = $this->getRabbitMqTestManager()->getQueue($metadata->getQueueName());
            $expected = [
                'name' => $metadata->getQueueName()
            ];
            static::assertEquals($expected, $actualQueue);

            $actualBindings = $this->getRabbitMqTestManager()->getBindingsByExchangeAndQueue($metadata->getExchangeName(), $metadata->getQueueName());
            $expected = $metadata->getBindingKeys();
            static::assertEquals($expected, $actualBindings);
        }
    }

    /**
     * Настраивает event bus manager на оснвое конфига
     *
     * @param ServiceLocatorInterface $appServiceManager
     * @param array                   $config
     */
    protected function buildEventBusManager(ServiceLocatorInterface $appServiceManager, array $config = [])
    {
        /** @var Module $module */
        $module = $appServiceManager->get(Module::class);

        if (array_key_exists('event_bus_manager', $config)) {
            $managerConfig = ArrayUtils::merge($module->getModuleOptions()->getEventBusManager(), $config['event_bus_manager']);
            $module->getModuleOptions()->setEventBusManager($managerConfig);
        }

        if (array_key_exists('driver', $config)) {
            $driverConfig = ArrayUtils::merge($module->getModuleOptions()->getDriver(), $config['driver']);
            $module->getModuleOptions()->setDriver($driverConfig);
        }
        if (array_key_exists('connection', $config)) {
            $connectionConfig = ArrayUtils::merge($module->getModuleOptions()->getConnection(), $config['connection']);
            $module->getModuleOptions()->setConnection($connectionConfig);
        }
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

        $appServiceManager = $this->getApplicationServiceLocator();


        $connectionConfig = $this->getRabbitMqConnectionForTest();


        $this->buildEventBusManager($appServiceManager, [
            'event_bus_manager' => [
                'example' => [
                    'driver' => 'example'
                ]
            ],
            'connection' => [
                'example' => [
                    'params' => $connectionConfig
                ]
            ],
            'driver' => [
                'example' => [
                    'pluginName' => RabbitMqDriver::class,
                    'connection' => 'example',
                    'paths' => [
                        __DIR__ . '/../../_files/Messages'
                    ]
                ]
            ]
        ]);

        /** @var EventBusManagerFacade $eventBusManager */
        $eventBusManager = $appServiceManager->get('event_bus.manager.example');

        /** @var EventBusDriverInterface|MetadataReaderInterface $driver */
        $driver = $eventBusManager->getDriver();
        $driver->initEventBus();

        $message = new Foo();
        $driver->trigger('create.procedure.app1', $message);
    }
}
