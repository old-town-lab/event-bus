<?php
/**
 * @link    https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\PhpUnit\Test\Driver;

use OldTown\EventBus\Driver\EventBusDriverInterface;
use OldTown\EventBus\Driver\ExtractorDataFromEventBusInterface;
use OldTown\EventBus\Message\EventBusMessagePluginManager;
use OldTown\EventBus\Message\MessageInterface;
use PHPUnit_Framework_TestCase;
use OldTown\EventBus\Driver\MessageHandler;
use PHPUnit_Framework_MockObject_MockObject;


/**
 * Class MessageHandlerTest
 *
 * @package OldTown\EventBus\PhpUnit\Test\Driver
 */
class MessageHandlerTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var MessageHandler
     */
    protected $messageHandler;

    /**
     *
     * @return void
     */
    protected function setUp()
    {
        $messageName = 'test_message_name';
        $callback = function () {};
        /** @var ExtractorDataFromEventBusInterface $driver */
        $driver = $this->getMock(ExtractorDataFromEventBusInterface::class);
        /** @var EventBusMessagePluginManager $messagePluginManager */
        $messagePluginManager = $this->getMock(EventBusMessagePluginManager::class);

        $this->messageHandler = new MessageHandler($messageName, $callback, $driver, $messagePluginManager);
        parent::setUp();
    }

    /**
     * Проверка установки/получения имени сообщения
     *
     */
    public function testGetterSetterMessageName()
    {
        $expected = 'test_name';
        static::assertEquals($this->messageHandler, $this->messageHandler->setMessageName($expected));
        static::assertEquals($expected, $this->messageHandler->getMessageName());
    }


    /**
     * Проверка установки/получения функции обратного вызова
     *
     */
    public function testGetterSetterCallback()
    {
        $expected = function () {};
        static::assertEquals($this->messageHandler, $this->messageHandler->setCallback($expected));
        static::assertEquals($expected, $this->messageHandler->getCallback());
    }


    /**
     * Проверка установки/получения драйвера
     *
     */
    public function testGetterSetterDriver()
    {
        /** @var EventBusDriverInterface $expected */
        $expected = $this->getMock(EventBusDriverInterface::class);
        static::assertEquals($this->messageHandler, $this->messageHandler->setDriver($expected));
        static::assertEquals($expected, $this->messageHandler->getDriver());
    }


    /**
     * Проверка установки/получения менеджера сообщений
     *
     */
    public function testMessagePluginManager()
    {
        /** @var EventBusMessagePluginManager $expected */
        $expected = $this->getMock(EventBusMessagePluginManager::class);
        static::assertEquals($this->messageHandler, $this->messageHandler->setMessagePluginManager($expected));
        static::assertEquals($expected, $this->messageHandler->getMessagePluginManager());
    }


    /**
     * Проверка установки/получения менеджера сообщений
     *
     * @expectedException \OldTown\EventBus\Driver\Exception\ErrorExtractSerializerNameException
     * @expectedExceptionMessage Драйвер должен реализовывать интерфейс OldTown\EventBus\Driver\ExtractorDataFromEventBusInterface
     */
    public function testHandlerInvalidDriver()
    {
        /** @var EventBusDriverInterface $expected */
        $expected = $this->getMock(EventBusDriverInterface::class);
        $this->messageHandler->setDriver($expected);

        $handler = $this->messageHandler;
        $handler();
    }


    /**
     * Проверка установки/получения менеджера сообщений
     *
     */
    public function testHandler()
    {
        $serializerName = 'test_serializer_name';
        $serializedData  = 'test_serialized_data';
        $expectedMessageName = $this->messageHandler->getMessageName();

        $args = [
            'arg_1',
            'arg_2'
        ];



        /** @var PHPUnit_Framework_MockObject_MockObject|EventBusDriverInterface $driver */
        $driver = $this->messageHandler->getDriver();
        $driver->expects(static::once())
               ->method('extractSerializerName')
               ->with(static::equalTo($args))
               ->will(static::returnValue($serializerName));
        $driver->expects(static::once())
               ->method('extractSerializedData')
               ->with(static::equalTo($args))
               ->will(static::returnValue($serializedData));


        /** @var MessageInterface|PHPUnit_Framework_MockObject_MockObject $messgae */
        $message = $this->getMock(MessageInterface::class);
        $message->expects(static::once())
            ->method('setSerializerName')
            ->with(static::equalTo($serializerName));
        $message->expects(static::once())
            ->method('setContent')
            ->with(static::equalTo($serializedData));

        /** @var EventBusMessagePluginManager|PHPUnit_Framework_MockObject_MockObject $messagePluginManager */
        $messagePluginManager = $this->messageHandler->getMessagePluginManager();
        $messagePluginManager->expects(static::once())
                             ->method('get')
                             ->with(static::equalTo($expectedMessageName))
                             ->will(static::returnValue($message));

        $expectedReturnCallback = 'test_return';
        $actualMessage = null;
        $callback = function ($msg) use ($expectedReturnCallback, &$actualMessage) {
            $actualMessage = $msg;
            return $expectedReturnCallback;
        };
        $this->messageHandler->setCallback($callback);


        static::assertEquals($expectedReturnCallback, call_user_func_array($this->messageHandler, $args));
        static::assertEquals($message, $actualMessage);
    }
}
