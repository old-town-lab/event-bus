<?php
/**
 * @link    https://github.com/old-town/event-buss
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBuss\PhpUnitTest\Driver;

use OldTown\EventBuss\Driver\RabbitMqDriver;
use OldTown\EventBuss\EventBussManager\EventBussManagerFacade;
use OldTown\EventBuss\Module;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\ArrayUtils;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use OldTown\EventBuss\Driver\RabbitMqDriver\Adapter\AmqpPhpExtension;
use OldTown\EventBuss\Driver\RabbitMqDriver\Adapter\AdapterInterface;
use OldTown\EventBuss\PhpUnitTest\RabbitMqTestCaseTrait;
use OldTown\EventBuss\PhpUnitTest\RabbitMqTestCaseInterface;

/**
 * Class DriverChainTest
 *
 * @package OldTown\EventBuss\PhpUnitTest\Driver
 */
class RabbitMqDriverTest extends AbstractHttpControllerTestCase implements RabbitMqTestCaseInterface
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

        $managerConfig = ArrayUtils::merge($module->getModuleOptions()->getEventBussManager(), [
            'example' => [
                'driver' => 'example'
            ]
        ]);
        $module->getModuleOptions()->setEventBussManager($managerConfig);

        $driverConfig = ArrayUtils::merge($module->getModuleOptions()->getDriver(), [
            'example' => [
                'pluginName' => RabbitMqDriver::class,
            ]
        ]);
        $module->getModuleOptions()->setDriver($driverConfig);

        /** @var EventBussManagerFacade $eventBussManager */
        $eventBussManager = $appServiceManager->get('event_buss.manager.example');

        /** @var RabbitMqDriver $driver */
        $driver = $eventBussManager->getDriver();

        $actualAdapterName = $driver->getAdapterName();

        static::assertEquals($actualAdapterName, AmqpPhpExtension::class);
    }


    /**
     * Получение имени адаптера используемого для работы с сервером очередей. Имя адаптера задается через конфиг.
     * Указан несуществующий класс
     *
     * @expectedException \OldTown\EventBuss\Driver\Exception\InvalidAdapterNameException
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

        $managerConfig = ArrayUtils::merge($module->getModuleOptions()->getEventBussManager(), [
            'example' => [
                'driver' => 'example'
            ]
        ]);
        $module->getModuleOptions()->setEventBussManager($managerConfig);

        $driverConfig = ArrayUtils::merge($module->getModuleOptions()->getDriver(), [
            'example' => [
                'pluginName' => RabbitMqDriver::class,
                'adapter' => 'invalid-adapter'
            ]
        ]);
        $module->getModuleOptions()->setDriver($driverConfig);

        /** @var EventBussManagerFacade $eventBussManager */
        $eventBussManager = $appServiceManager->get('event_buss.manager.example');

        /** @var RabbitMqDriver $driver */
        $driver = $eventBussManager->getDriver();

        $driver->getAdapterName();
    }


    /**
     * Получение имени адаптера используемого для работы с сервером очередей. Имя адаптера задается через конфиг.
     * Указан класс который не реализует интерфейс адаптера
     *
     * @expectedException \OldTown\EventBuss\Driver\Exception\InvalidAdapterNameException
     * @expectedExceptionMessage Адаптер должен реализовывать OldTown\EventBuss\Driver\RabbitMqDriver\Adapter\AdapterInterface
     */
    public function testGetInvalidAdapterFromExtraOptions()
    {
        $this->setApplicationConfig(
            include __DIR__ . '/../../_files/application.config.php'
        );

        $appServiceManager = $this->getApplicationServiceLocator();
        /** @var Module $module */
        $module = $appServiceManager->get(Module::class);

        $managerConfig = ArrayUtils::merge($module->getModuleOptions()->getEventBussManager(), [
            'example' => [
                'driver' => 'example'
            ]
        ]);
        $module->getModuleOptions()->setEventBussManager($managerConfig);

        $driverConfig = ArrayUtils::merge($module->getModuleOptions()->getDriver(), [
            'example' => [
                'pluginName' => RabbitMqDriver::class,
                'adapter' => \stdClass::class
            ]
        ]);
        $module->getModuleOptions()->setDriver($driverConfig);

        /** @var EventBussManagerFacade $eventBussManager */
        $eventBussManager = $appServiceManager->get('event_buss.manager.example');

        /** @var RabbitMqDriver $driver */
        $driver = $eventBussManager->getDriver();

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

        $managerConfig = ArrayUtils::merge($module->getModuleOptions()->getEventBussManager(), [
            'example' => [
                'driver' => 'example'
            ]
        ]);
        $module->getModuleOptions()->setEventBussManager($managerConfig);


        $adapter = $this->getMock(AdapterInterface::class);
        $expectedAdapterName = get_class($adapter);

        $driverConfig = ArrayUtils::merge($module->getModuleOptions()->getDriver(), [
            'example' => [
                'pluginName' => RabbitMqDriver::class,
                'adapter' => $expectedAdapterName
            ]
        ]);
        $module->getModuleOptions()->setDriver($driverConfig);

        /** @var EventBussManagerFacade $eventBussManager */
        $eventBussManager = $appServiceManager->get('event_buss.manager.example');

        /** @var RabbitMqDriver $driver */
        $driver = $eventBussManager->getDriver();

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

        $managerConfig = ArrayUtils::merge($module->getModuleOptions()->getEventBussManager(), [
            'example' => [
                'driver' => 'example'
            ]
        ]);
        $module->getModuleOptions()->setEventBussManager($managerConfig);


        $adapter = $this->getMock(AdapterInterface::class);
        $adapterName = get_class($adapter);

        $driverConfig = ArrayUtils::merge($module->getModuleOptions()->getDriver(), [
            'example' => [
                'pluginName' => RabbitMqDriver::class,
                'adapter' => $adapterName
            ]
        ]);
        $module->getModuleOptions()->setDriver($driverConfig);

        /** @var EventBussManagerFacade $eventBussManager */
        $eventBussManager = $appServiceManager->get('event_buss.manager.example');

        /** @var RabbitMqDriver $driver */
        $driver = $eventBussManager->getDriver();

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

        $managerConfig = ArrayUtils::merge($module->getModuleOptions()->getEventBussManager(), [
            'example' => [
                'driver' => 'example'
            ]
        ]);
        $module->getModuleOptions()->setEventBussManager($managerConfig);


        $adapterMock = $this->getMock(AdapterInterface::class);
        $adapterName = get_class($adapterMock);

        $driverConfig = ArrayUtils::merge($module->getModuleOptions()->getDriver(), [
            'example' => [
                'pluginName' => RabbitMqDriver::class,
                'adapter' => $adapterName
            ]
        ]);
        $module->getModuleOptions()->setDriver($driverConfig);

        /** @var EventBussManagerFacade $eventBussManager */
        $eventBussManager = $appServiceManager->get('event_buss.manager.example');

        /** @var RabbitMqDriver $driver */
        $driver = $eventBussManager->getDriver();

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
     * @expectedException \OldTown\EventBuss\Driver\RabbitMqDriver\Adapter\Exception\AmqpPhpExtensionNotInstalledException
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
    public function testInitEventBuss()
    {
        $this->setApplicationConfig(
            include __DIR__ . '/../../_files/application.config.php'
        );

        $appServiceManager = $this->getApplicationServiceLocator();


        $connectionConfig = $this->getRabbitMqConnectionForTest();


        $this->buildEventBussManager($appServiceManager, [
            'event_buss_manager' => [
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

        /** @var EventBussManagerFacade $eventBussManager */
        $eventBussManager = $appServiceManager->get('event_buss.manager.example');

        $eventBussManager->getDriver()->initEventBuss();
    }

    /**
     * Настраивает event buss manager на оснвое конфига
     *
     * @param ServiceLocatorInterface $appServiceManager
     * @param array                   $config
     */
    protected function buildEventBussManager(ServiceLocatorInterface $appServiceManager, array $config = [])
    {
        /** @var Module $module */
        $module = $appServiceManager->get(Module::class);

        if (array_key_exists('event_buss_manager', $config)) {
            $managerConfig = ArrayUtils::merge($module->getModuleOptions()->getEventBussManager(), $config['event_buss_manager']);
            $module->getModuleOptions()->setEventBussManager($managerConfig);
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
        /** @var Module $module */
        $module = $appServiceManager->get(Module::class);

        $managerConfig = ArrayUtils::merge($module->getModuleOptions()->getEventBussManager(), [
            'example' => [
                'driver' => 'example'
            ]
        ]);
        $module->getModuleOptions()->setEventBussManager($managerConfig);

        $driverConfig = ArrayUtils::merge($module->getModuleOptions()->getDriver(), [
            'example' => [
                'pluginName' => RabbitMqDriver::class,
                'paths' => [
                    __DIR__ . '/../../_files/Messages'
                ]
            ]
        ]);
        $module->getModuleOptions()->setDriver($driverConfig);

        /** @var EventBussManagerFacade $eventBussManager */
        $eventBussManager = $appServiceManager->get('event_buss.manager.example');

        $eventBussManager->getDriver()->trigger('test');
    }
}
