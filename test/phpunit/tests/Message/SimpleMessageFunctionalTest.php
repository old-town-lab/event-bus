<?php
/**
 * @link https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\PhpUnit\Test\Message;

use OldTown\EventBus\Message\EventBusMessagePluginManager;
use OldTown\EventBus\PhpUnit\TestData\TestPaths;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use \OldTown\EventBus\PhpUnit\TestData\SimpleMessage\Foo;

/**
 * Class SimpleMessageFunctionalTest
 *
 * @package OldTown\EventBus\PhpUnit\Test\Message
 */
class SimpleMessageFunctionalTest extends AbstractHttpControllerTestCase
{
    /**
     * Тестирование гидратора по умолчанию
     */
    public function testObjectPropertyHydrator()
    {
        /** @noinspection PhpIncludeInspection */
        $this->setApplicationConfig(
            include TestPaths::getApplicationConfig()
        );
        /** @var EventBusMessagePluginManager $manager */
        $manager = $this->getApplicationServiceLocator()->get(EventBusMessagePluginManager::class);

        /** @var Foo $message */
        $message = $manager->get(Foo::class);

        $actual = $message->getHydrator()->extract($message);

        $expected = $message->toArray();

        static::assertEquals($expected, $actual);
    }
}
