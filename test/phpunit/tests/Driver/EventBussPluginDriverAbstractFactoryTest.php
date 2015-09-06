<?php
/**
 * @link    https://github.com/old-town/event-buss
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBuss\PhpUnit\Test\Driver;

use OldTown\EventBuss\Driver\ConnectionDriverInterface;
use OldTown\EventBuss\Driver\DriverConfig;
use OldTown\EventBuss\Driver\EventBussPluginDriverAbstractFactory;
use Zend\ServiceManager\ServiceManager;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use OldTown\EventBuss\Driver\RabbitMqDriver;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use OldTown\EventBuss\Driver\EventBussDriverPluginManager;
use OldTown\EventBuss\Module;

/**
 * Class EventBussPluginDriverAbstractFactoryTest
 *
 * @package OldTown\EventBuss\PhpUnit\Test\Driver
 */
class EventBussPluginDriverAbstractFactoryTest extends AbstractHttpControllerTestCase
{
    /**
     * Отсутствует сервис менеджер приложения
     *
     * @expectedException \OldTown\EventBuss\Driver\Exception\RuntimeException
     * @expectedExceptionMessage Не удалось получить ServiceLocator
     */
    public function testNoAppServiceLocator()
    {
        try {
            $this->setApplicationConfig(
                include __DIR__ . '/../../_files/application.config.php'
            );

            /** @var ServiceManager $appServiceLocator */
            $appServiceManager = $this->getApplicationServiceLocator();

            $factory = new EventBussPluginDriverAbstractFactory();
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
     * @expectedException \OldTown\EventBuss\Driver\Exception\RuntimeException
     * @expectedExceptionMessage Не удалось получить модуль: OldTown\EventBuss\Module
     */
    public function testNoModuleServiceLocator()
    {
        try {
            $this->setApplicationConfig(
                include __DIR__ . '/../../_files/application.config.php'
            );

            /** @var ServiceManager $appServiceLocator */
            $appServiceManager = $this->getApplicationServiceLocator();

            /** @var EventBussDriverPluginManager $eventBussDriverManager */
            $eventBussDriverManager = $appServiceManager->get('eventBussDriverManager');

            $appServiceManager->setAllowOverride(true);
            $appServiceManager->setService(Module::class, new \stdClass());


            $eventBussDriverManager->get(RabbitMqDriver::class, [
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
     * @expectedException \OldTown\EventBuss\Driver\Exception\ConnectionNotFoundException
     * @expectedExceptionMessage Отсутствует соеденение с именем: example
     */
    public function testInvalidConnectionName()
    {
        try {
            $this->setApplicationConfig(
                include __DIR__ . '/../../_files/application.config.php'
            );

            /** @var ServiceManager $appServiceLocator */
            $appServiceManager = $this->getApplicationServiceLocator();

            /** @var EventBussDriverPluginManager $eventBussDriverManager */
            $eventBussDriverManager = $appServiceManager->get('eventBussDriverManager');

            $eventBussDriverManager->get(RabbitMqDriver::class, [
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
        $this->setApplicationConfig(
            include __DIR__ . '/../../_files/application.config.php'
        );

        /** @var ServiceManager $appServiceLocator */
        $appServiceManager = $this->getApplicationServiceLocator();
        $appServiceManager->setAllowOverride(true);
        /** @var array $appConfig */
        $appConfig = $appServiceManager->get('config');
        $appConfig['event_buss']['connection']['example'] = [
            'params' => [
                'host'     => 'localhost',
                'port'     => '5672',
                'vhost'    => '/',
                'login'    => 'guest',
                'password' => 'guest'
            ]
        ];

        $appServiceManager->setService('config', $appConfig);

        /** @var EventBussDriverPluginManager $eventBussDriverManager */
        $eventBussDriverManager = $appServiceManager->get('eventBussDriverManager');

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
        $driver = $eventBussDriverManager->get(RabbitMqDriver::class, [
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
        $this->setApplicationConfig(
            include __DIR__ . '/../../_files/application.config.php'
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

        $appConfig['event_buss']['connection']['example'] = $expected;

        $appServiceManager->setService('config', $appConfig);

        /** @var EventBussDriverPluginManager $eventBussDriverManager */
        $eventBussDriverManager = $appServiceManager->get('eventBussDriverManager');



        /** @var ConnectionDriverInterface $driver */
        $driver = $eventBussDriverManager->get(RabbitMqDriver::class, [
            DriverConfig::CONNECTION => 'example',
        ]);

        $actual = $driver->getConnectionConfig();
        static::assertEquals($expected, $actual);
    }
}
