<?php
/**
 * @link https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\PhpUnit\Test;

use OldTown\EventBus\Driver\EventBusDriverPluginManager;
use OldTown\EventBus\EventBusManager\EventBusPluginManager;
use OldTown\EventBus\Message\EventBusMessagePluginManager;
use OldTown\EventBus\PhpUnit\TestData\TestPaths;
use Zend\ModuleManager\ModuleManagerInterface;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use OldTown\EventBus\Module;
use OldTown\EventBus\Options\ModuleOptions;


/**
 * Class ModuleTest
 *
 * @package OldTown\EventBus\PhpUnit\Test
 */
class ModuleTest extends AbstractHttpControllerTestCase
{
    /**
     *
     * @return void
     */
    public function testLoadModule()
    {
        /** @noinspection PhpIncludeInspection */
        $this->setApplicationConfig(
            include TestPaths::getApplicationConfig()
        );
        $this->assertModulesLoaded(['OldTown\EventBus']);
    }

    /**
     *
     * @return void
     */
    public function testModuleLocatorRegistered()
    {
        /** @noinspection PhpIncludeInspection */
        $this->setApplicationConfig(
            include TestPaths::getApplicationConfig()
        );
        $module = $this->getApplicationServiceLocator()->get(Module::class);

        static::assertInstanceOf(Module::class, $module);
    }

    /**
     *
     * @return void
     */
    public function testModuleServiceLocator()
    {
        /** @noinspection PhpIncludeInspection */
        $this->setApplicationConfig(
            include TestPaths::getApplicationConfig()
        );
        /** @var Module $module */
        $module = $this->getApplicationServiceLocator()->get(Module::class);

        $actualServiceLocator = $module->getServiceLocator();


        static::assertTrue($actualServiceLocator === $this->getApplicationServiceLocator());
    }


    /**
     *
     * @return void
     */
    public function testGetModuleOptions()
    {
        /** @noinspection PhpIncludeInspection */
        $this->setApplicationConfig(
            include TestPaths::getApplicationConfig()
        );
        /** @var Module $module */
        $module = $this->getApplicationServiceLocator()->get(Module::class);


        static::assertInstanceOf(ModuleOptions::class, $module->getModuleOptions());
    }

    /**
     * @expectedException \OldTown\EventBus\Exception\ErrorInitModuleException
     * @expectedExceptionMessage Менеджер модулей должен реализовывать Zend\ModuleManager\ModuleManager
     *
     * @return void
     */
    public function testInitBadModuleManager()
    {
        /** @noinspection PhpIncludeInspection */
        $this->setApplicationConfig(
            include TestPaths::getApplicationConfig()
        );
        /** @var Module $module */
        $module = $this->getApplicationServiceLocator()->get(Module::class);

        $badManager = $this->getMock(ModuleManagerInterface::class);

        /** @noinspection PhpParamsInspection */
        $module->init($badManager);
    }


    /**
     * Проверяем что инициируется плагин менеджер для работы с драйверами EventBus
     *
     * @return void
     */
    public function testInitEventBusDriverPluginManager()
    {
        /** @noinspection PhpIncludeInspection */
        $this->setApplicationConfig(
            include TestPaths::getApplicationConfig()
        );

        $pluginManager = $this->getApplicationServiceLocator()->get('eventBusDriverManager');

        static::assertInstanceOf(EventBusDriverPluginManager::class, $pluginManager);
    }


    /**
     * Проверяем что инициируется плагин менеджер для работы с драйверами EventBus
     *
     * @return void
     */
    public function testInitEventBusPluginManager()
    {
        /** @noinspection PhpIncludeInspection */
        $this->setApplicationConfig(
            include TestPaths::getApplicationConfig()
        );

        $pluginManager = $this->getApplicationServiceLocator()->get('eventBusPluginManager');

        static::assertInstanceOf(EventBusPluginManager::class, $pluginManager);
    }

    /**
     * Проверяем что инициируется плагин менеджер для работы с сообщениями
     *
     * @return void
     */
    public function testInitEventMessagePluginManager()
    {
        /** @noinspection PhpIncludeInspection */
        $this->setApplicationConfig(
            include TestPaths::getApplicationConfig()
        );

        $pluginManager = $this->getApplicationServiceLocator()->get('eventBusMessageManager');

        static::assertInstanceOf(EventBusMessagePluginManager::class, $pluginManager);
    }
}
