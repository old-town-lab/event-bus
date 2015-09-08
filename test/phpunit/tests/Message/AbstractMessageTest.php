<?php
/**
 * @link https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\PhpUnit\Test\Message;

use PHPUnit_Framework_TestCase;
use OldTown\EventBus\Message\AbstractMessage;
use PHPUnit_Framework_MockObject_MockObject;
use Zend\Serializer\Adapter\AdapterInterface as Serializer;
use Zend\Serializer\Adapter\Json as JsonSerializer;


/**
 * Class AbstractMessageTest
 *
 * @package OldTown\EventBus\PhpUnit\Test\Message
 */
class AbstractMessageTest extends PHPUnit_Framework_TestCase
{
    /**
     * Проверка получения/установки опций сериалайзера
     *
     */
    public function testGetterSetterSerializerOptions()
    {
        /** @var PHPUnit_Framework_MockObject_MockObject|AbstractMessage  $message */
        $message = $this->getMockForAbstractClass(AbstractMessage::class);

        $expected = [
            'test_key_1' => 'test_value_1',
            'test_key_2' => 'test_value_2',
            'test_key_3' => 'test_value_3'
        ];

        static::assertTrue($message->setSerializerOptions($expected) === $message);

        $actual = $message->getSerializerOptions();

        static::assertEquals($expected, $actual);
    }

    /**
     * Проверка получения/установки имени сериалайзера
     *
     */
    public function testGetterSetterSerializerName()
    {
        /** @var PHPUnit_Framework_MockObject_MockObject|AbstractMessage  $message */
        $message = $this->getMockForAbstractClass(AbstractMessage::class);

        $expected = 'test_serializer_name';

        static::assertTrue($message->setSerializerName($expected) === $message);

        $actual = $message->getSerializerName();

        static::assertEquals($expected, $actual);
    }


    /**
     * Проверка получения/установки сериалайзера
     *
     */
    public function testGetterSetterSerializer()
    {
        /** @var PHPUnit_Framework_MockObject_MockObject|AbstractMessage  $message */
        $message = $this->getMockForAbstractClass(AbstractMessage::class);

        /** @var PHPUnit_Framework_MockObject_MockObject|Serializer $expected */
        $expected = $this->getMock(Serializer::class);

        static::assertTrue($message->setSerializer($expected) === $message);

        $actual = $message->getSerializer();

        static::assertEquals($expected, $actual);
    }

    /**
     * Проверка получения/установки сериалайзера
     *
     */
    public function testGetDefaultSerializer()
    {
        /** @var PHPUnit_Framework_MockObject_MockObject|AbstractMessage  $message */
        $message = $this->getMockForAbstractClass(AbstractMessage::class);
        $actual = $message->getSerializer();

        static::assertInstanceOf(JsonSerializer::class, $actual);
    }
}
