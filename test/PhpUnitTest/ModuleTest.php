<?php
/**
 * @link https://github.com/old-town/event-buss
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBuss\PhpUnitTest;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;


/**
 * Class ModuleTest
 *
 * @package OldTown\EventBuss\PhpUnitTest
 */
class ModuleTest extends AbstractHttpControllerTestCase
{
    /**
     *
     */
    public function testLoadModule()
    {
        $this->setApplicationConfig(
            include __DIR__ . '/../_files/application.config.php'
        );
        $this->assertModulesLoaded(['OldTown\EventBuss']);
    }
}
