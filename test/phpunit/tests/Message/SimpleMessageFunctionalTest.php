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
     * Проверка работы гидратора - extract
     */
    public function testExtract()
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

    /**
     * Проверка работы гидратора - hydrate
     */
    public function testHydrate()
    {
        /** @noinspection PhpIncludeInspection */
        $this->setApplicationConfig(
            include TestPaths::getApplicationConfig()
        );
        /** @var EventBusMessagePluginManager $manager */
        $manager = $this->getApplicationServiceLocator()->get(EventBusMessagePluginManager::class);

        /** @var Foo $message */
        $message = $manager->get(Foo::class);
        $message->setTestProperty1(null);
        $message->setTestProperty2(null);
        $message->setTestProperty3(null);


        $expected = [
            'testProperty1' => 'abrakadabra',
            'testProperty2' => false,
            'testProperty3' => []
        ];


        $message->getHydrator()->hydrate($expected, $message);

        $actual = $message->toArray();

        static::assertEquals($expected, $actual);
    }



    /**
     * Проверка работы гидратора - hydrate
     */
    public function testValidate()
    {
        /** @noinspection PhpIncludeInspection */
        $this->setApplicationConfig(
            include TestPaths::getApplicationConfig()
        );
        /** @var EventBusMessagePluginManager $manager */
        $manager = $this->getApplicationServiceLocator()->get(EventBusMessagePluginManager::class);

        /** @var Foo $message */
        $message = $manager->get(Foo::class);

        $data = [
            'testProperty1' => 'abrakadabra',
            'testProperty2' => false,
            'testProperty3' => []
        ];

        $isValid = $message->getValidator()->isValid($data);

        static::assertTrue($isValid);
    }
}
