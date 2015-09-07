<?php
/**
 * @link https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\PhpUnit\Test;

use OldTown\EventBus\Driver\EventBussDriverPluginManager;
use OldTown\EventBus\EventBussManager\EventBussPluginManager;
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
        $this->setApplicationConfig(
            include __DIR__ . '/../_files/application.config.php'
        );
        $this->assertModulesLoaded(['OldTown\EventBus']);
    }

    /**
     *
     * @return void
     */
    public function testModuleLocatorRegistered()
    {
        $this->setApplicationConfig(
            include __DIR__ . '/../_files/application.config.php'
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
        $this->setApplicationConfig(
            include __DIR__ . '/../_files/application.config.php'
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
        $this->setApplicationConfig(
            include __DIR__ . '/../_files/application.config.php'
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
        $this->setApplicationConfig(
            include __DIR__ . '/../_files/application.config.php'
        );
        /** @var Module $module */
        $module = $this->getApplicationServiceLocator()->get(Module::class);

        $badManager = $this->getMock(ModuleManagerInterface::class);

        /** @noinspection PhpParamsInspection */
        $module->init($badManager);
    }


    /**
     * Проверяем что инициируется плагин менеджер для работы с драйверами EventBuss
     *
     * @return void
     */
    public function testInitEventBussDriverPluginManager()
    {
        $this->setApplicationConfig(
            include __DIR__ . '/../_files/application.config.php'
        );

        $pluginManager = $this->getApplicationServiceLocator()->get('eventBussDriverManager');

        static::assertInstanceOf(EventBussDriverPluginManager::class, $pluginManager);
    }


    /**
     * Проверяем что инициируется плагин менеджер для работы с драйверами EventBuss
     *
     * @return void
     */
    public function testInitEventBussPluginManager()
    {
        $this->setApplicationConfig(
            include __DIR__ . '/../_files/application.config.php'
        );

        $pluginManager = $this->getApplicationServiceLocator()->get('eventBussPluginManager');

        static::assertInstanceOf(EventBussPluginManager::class, $pluginManager);
    }
}
