<?php
/**
 * @link https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\PhpUnit\Test\EventBusManager;

use OldTown\EventBus\EventBusManager\EventBusManagerFacade;
use OldTown\EventBus\Driver\EventBusDriverInterface;
use OldTown\EventBus\Message\MessageInterface;
use PHPUnit_Framework_TestCase;
use PHPUnit_Framework_MockObject_MockObject;

/**
 * Class EventBusManagerTest
 *
 * @package OldTown\EventBus\PhpUnit\Test\EventBusManagerFacade
 */
class EventBusManagerFacadeTest extends PHPUnit_Framework_TestCase
{
    /**
     * Создаем стандартного EventBusManagerFacade
     *
     */
    public function testCreateEventBusDefaultManager()
    {
        /** @var EventBusDriverInterface $driver */
        $driver = $this->getMock(EventBusDriverInterface::class);
        $manager = new EventBusManagerFacade($driver);

        static::assertInstanceOf(EventBusManagerFacade::class, $manager);
    }



    /**
     * Создаем стандартного EventBusManagerFacade
     *
     */
    public function testTrigger()
    {
        /** @var PHPUnit_Framework_MockObject_MockObject|EventBusDriverInterface  $driver */
        $driver = $this->getMock(EventBusDriverInterface::class);
        $driver->expects(static::once())->method('trigger');
        $manager = new EventBusManagerFacade($driver);
        /** @var PHPUnit_Framework_MockObject_MockObject|MessageInterface $message */
        $message = $this->getMock(MessageInterface::class);
        $manager->trigger('test', $message);
    }


    /**
     * Создаем стандартного EventBusManagerFacade
     *
     */
    public function testListener()
    {
        /** @var PHPUnit_Framework_MockObject_MockObject|EventBusDriverInterface  $driver */
        $driver = $this->getMock(EventBusDriverInterface::class);
        $manager = new EventBusManagerFacade($driver);
        /** @var PHPUnit_Framework_MockObject_MockObject|MessageInterface $message */
        $message = $this->getMock(MessageInterface::class);
        $manager->listener($message, function () {

        });
    }
}
