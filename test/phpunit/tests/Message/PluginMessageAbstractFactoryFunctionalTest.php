<?php
/**
 * @link https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\PhpUnit\Test\Message;

use OldTown\EventBus\PhpUnit\TestData\TestPaths;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

/**
 * Class AbstractMessageTest
 *
 * @package OldTown\EventBus\PhpUnit\Test\Message
 */
class PluginMessageAbstractFactoryFunctionalTest extends AbstractHttpControllerTestCase
{
    /**
     * Тест корректности определения того что сервис может быть создан фабрикой
     *
     */
    public function testCanCreateServiceWithName()
    {
        /** @noinspection PhpIncludeInspection */
        $this->setApplicationConfig(
            include TestPaths::getApplicationConfig()
        );
    }
}
