<?php
/**
 * @link https://github.com/old-town/event-buss
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBuss\PhpUnitTest;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use OldTown\EventBuss\Module;
use OldTown\EventBuss\Options\ModuleOptions;


/**
 * Class ModuleTest
 *
 * @package OldTown\EventBuss\PhpUnitTest
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
        $this->assertModulesLoaded(['OldTown\EventBuss']);
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
}
