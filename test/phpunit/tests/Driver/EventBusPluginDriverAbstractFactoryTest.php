<?php
/**
 * @link    https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\PhpUnit\Test\Driver;

use OldTown\EventBus\Driver\ConnectionDriverInterface;
use OldTown\EventBus\Driver\DriverConfig;
use OldTown\EventBus\Driver\EventBusPluginDriverAbstractFactory;
use OldTown\EventBus\PhpUnit\TestData\TestPaths;
use Zend\ServiceManager\ServiceManager;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use OldTown\EventBus\Driver\RabbitMqDriver;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use OldTown\EventBus\Driver\EventBusDriverPluginManager;
use OldTown\EventBus\Module;

/**
 * Class EventBusPluginDriverAbstractFactoryTest
 *
 * @package OldTown\EventBus\PhpUnit\Test\Driver
 */
class EventBusPluginDriverAbstractFactoryTest extends AbstractHttpControllerTestCase
{
    /**
     * Отсутствует сервис менеджер приложения
     *
     * @expectedException \OldTown\EventBus\Driver\Exception\RuntimeException
     * @expectedExceptionMessage Не удалось получить ServiceLocator
     */
    public function testNoAppServiceLocator()
    {
        try {
            /** @noinspection PhpIncludeInspection */
            $this->setApplicationConfig(
                include TestPaths::getApplicationConfig()
            );

            /** @var ServiceManager $appServiceLocator */
            $appServiceManager = $this->getApplicationServiceLocator();

            $factory = new EventBusPluginDriverAbstractFactory();
            $appServiceManager->addAbstractFactory($factory);

            $appServiceManager->get(RabbitMqDriver::class);
        } catch (ServiceNotCreatedException $e) {
            if (($parentException = $e->getPrevious()) && ($prev = $parentException->getPrevious())) {
                throw $prev;
            }
        }
    }


    /**
     * Некорректный модуль
     *
     * @expectedException \OldTown\EventBus\Driver\Exception\RuntimeException
     * @expectedExceptionMessage Не удалось получить модуль: OldTown\EventBus\Module
     */
    public function testNoModuleServiceLocator()
    {
        try {
            /** @noinspection PhpIncludeInspection */
            $this->setApplicationConfig(
                include TestPaths::getApplicationConfig()
            );

            /** @var ServiceManager $appServiceLocator */
            $appServiceManager = $this->getApplicationServiceLocator();

            /** @var EventBusDriverPluginManager $eventBusDriverManager */
            $eventBusDriverManager = $appServiceManager->get('eventBusDriverManager');

            $appServiceManager->setAllowOverride(true);
            $appServiceManager->setService(Module::class, new \stdClass());


            $eventBusDriverManager->get(RabbitMqDriver::class, [
                DriverConfig::CONNECTION => 'example'
            ]);
        } catch (ServiceNotCreatedException $e) {
            if (($parentException = $e->getPrevious()) && ($prev = $parentException->getPrevious())) {
                throw $prev;
            }
        }
    }


    /**
     * Некорректное имя соеденения
     *
     * @expectedException \OldTown\EventBus\Driver\Exception\ConnectionNotFoundException
     * @expectedExceptionMessage Отсутствует соеденение с именем: example
     */
    public function testInvalidConnectionName()
    {
        try {
            /** @noinspection PhpIncludeInspection */
            $this->setApplicationConfig(
                include TestPaths::getApplicationConfig()
            );
            /** @var ServiceManager $appServiceLocator */
            $appServiceManager = $this->getApplicationServiceLocator();

            /** @var EventBusDriverPluginManager $eventBusDriverManager */
            $eventBusDriverManager = $appServiceManager->get('eventBusDriverManager');

            $eventBusDriverManager->get(RabbitMqDriver::class, [
                DriverConfig::CONNECTION => 'example'
            ]);
        } catch (ServiceNotCreatedException $e) {
            if (($parentException = $e->getPrevious()) && ($prev = $parentException->getPrevious())) {
                throw $prev;
            }
        }
    }


    /**
     * Проверка объеденения конфига соеденения с секцией connectionConfig драйвера
     *
     */
    public function testMergeConnectionConfig()
    {
        /** @noinspection PhpIncludeInspection */
        $this->setApplicationConfig(
            include TestPaths::getApplicationConfig()
        );
        /** @var ServiceManager $appServiceLocator */
        $appServiceManager = $this->getApplicationServiceLocator();
        $appServiceManager->setAllowOverride(true);
        /** @var array $appConfig */
        $appConfig = $appServiceManager->get('config');
        $appConfig['event_bus']['connection']['example'] = [
            'params' => [
                'host'     => 'localhost',
                'port'     => '5672',
                'vhost'    => '/',
                'login'    => 'guest',
                'password' => 'guest'
            ]
        ];

        $appServiceManager->setService('config', $appConfig);

        /** @var EventBusDriverPluginManager $eventBusDriverManager */
        $eventBusDriverManager = $appServiceManager->get('eventBusDriverManager');

        $expected = [
            'params' => [
                'host'     => 'example',
                'port'     => 'example',
                'vhost'    => 'example',
                'login'    => 'example',
                'password' => 'example'
            ]
        ];

        /** @var ConnectionDriverInterface $driver */
        $driver = $eventBusDriverManager->get(RabbitMqDriver::class, [
            DriverConfig::CONNECTION => 'example',
            DriverConfig::CONNECTION_CONFIG => $expected
        ]);

        $actual = $driver->getConnectionConfig();
        static::assertEquals($expected, $actual);
    }


    /**
     * Проверка получения конфига из настроек приложения
     *
     */
    public function testConnectionConfig()
    {
        /** @noinspection PhpIncludeInspection */
        $this->setApplicationConfig(
            include TestPaths::getApplicationConfig()
        );

        /** @var ServiceManager $appServiceLocator */
        $appServiceManager = $this->getApplicationServiceLocator();
        $appServiceManager->setAllowOverride(true);
        /** @var array $appConfig */
        $appConfig = $appServiceManager->get('config');

        $expected = [
            'params' => [
                'host'     => 'example',
                'port'     => 'example',
                'vhost'    => 'example',
                'login'    => 'example',
                'password' => 'example'
            ]
        ];

        $appConfig['event_bus']['connection']['example'] = $expected;

        $appServiceManager->setService('config', $appConfig);

        /** @var EventBusDriverPluginManager $eventBusDriverManager */
        $eventBusDriverManager = $appServiceManager->get('eventBusDriverManager');



        /** @var ConnectionDriverInterface $driver */
        $driver = $eventBusDriverManager->get(RabbitMqDriver::class, [
            DriverConfig::CONNECTION => 'example',
        ]);

        $actual = $driver->getConnectionConfig();
        static::assertEquals($expected, $actual);
    }
}
