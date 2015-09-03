<?php
/**
 * @link    https://github.com/old-town/event-buss
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBuss\PhpUnitTest\Driver;

use OldTown\EventBuss\Driver\RabbitMqDriver;
use OldTown\EventBuss\EventBussManager\EventBussManagerFacade;
use OldTown\EventBuss\Module;
use Zend\Stdlib\ArrayUtils;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use OldTown\EventBuss\Driver\RabbitMqDriver\Adapter\AmqpPhpExtension;
use OldTown\EventBuss\Driver\RabbitMqDriver\Adapter\AdapterInterface;


/**
 * Class DriverChainTest
 *
 * @package OldTown\EventBuss\PhpUnitTest\Driver
 */
class RabbitMqDriverTest extends AbstractHttpControllerTestCase
{
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
     *
     *
     */
    public function testInitEventBuss()
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

        $eventBussManager->getDriver()->initEventBuss();
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
