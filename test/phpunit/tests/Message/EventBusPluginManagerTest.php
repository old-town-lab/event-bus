<?php
/**
 * @link https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\PhpUnit\Test\Message;

use \OldTown\EventBus\Message\EventBusMessagePluginManager;
use PHPUnit_Framework_TestCase;
use \OldTown\EventBus\Message\MessageInterface;


/**
 * Class EventBusMessagePluginManagerTest
 *
 * @package OldTown\EventBus\PhpUnit\Test\Message
 */
class EventBusMessagePluginManagerTest extends PHPUnit_Framework_TestCase
{
    /**
     * Проверка создания не валидного EventBusManagerFacade
     *
     * @expectedException \OldTown\EventBus\Message\Exception\InvalidEventBusMessageException
     * @expectedExceptionMessage Класс сообщения ддолжен реализовывать OldTown\EventBus\Message\MessageInterface
     *
     * @throws \OldTown\EventBus\Message\Exception\InvalidEventBusMessageException
     */
    public function testNotValidPlugin()
    {
        $factory = new EventBusMessagePluginManager();

        $plugin = new \stdClass();
        $factory->validatePlugin($plugin);
    }

    /**
     * Проверка создания не валидного EventBusManagerFacade
     *
     * @throws \OldTown\EventBus\Message\Exception\InvalidEventBusMessageException
     */
    public function testValidPlugin()
    {
        $factory = new EventBusMessagePluginManager();

        $plugin = $this->getMock(MessageInterface::class);
        $factory->validatePlugin($plugin);
    }
}
