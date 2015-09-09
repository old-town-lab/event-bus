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
use Zend\Stdlib\Hydrator\HydratorInterface;
use Zend\Json\Json;
use Zend\Validator\ValidatorInterface;

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



    /**
     * Проверка получения контента. В качетсе сериализатора используется стандартный - json
     *
     */
    public function testGetContent()
    {
        /** @var PHPUnit_Framework_MockObject_MockObject|AbstractMessage  $message */
        $message = $this->getMockForAbstractClass(AbstractMessage::class);

        $extractResult = [
            'key_1' => 'value_1',
            'key_2' => 'value_2',
            'key_3' => 'value_3',
            'key_4' => 'value_4'
        ];

        $hydratorMock = $this->getMock(HydratorInterface::class, get_class_methods(HydratorInterface::class));
        $hydratorMock->expects(static::once())->method('extract')->will(static::returnValue($extractResult));

        $message->expects(static::once())->method('getHydrator')->will(static::returnValue($hydratorMock));

        $actual = $message->getContent();

        static::assertEquals(Json::encode($extractResult), $actual);
    }



    /**
     * Тестирование заполнения сообщения из строки. В качетсе сериализатора используется стандартный - json
     *
     */
    public function testFromString()
    {
        $unserializedData = [
            'key_1' => 'value_1',
            'key_2' => 'value_2',
            'key_3' => 'value_3',
            'key_4' => 'value_4'
        ];
        $serializedData = Json::encode($unserializedData);

        //Mock объект для соощения
        /** @var PHPUnit_Framework_MockObject_MockObject|AbstractMessage  $message */
        $message = $this->getMockForAbstractClass(AbstractMessage::class);

        //Mock объект для валидатора
        /** @var PHPUnit_Framework_MockObject_MockObject|ValidatorInterface $validatorMock */
        $validatorMock = $this->getMock(ValidatorInterface::class, get_class_methods(ValidatorInterface::class));
        $validatorMock->expects(static::once())
                      ->method('isValid')
                      ->with(static::equalTo($unserializedData))
                      ->will(static::returnValue(true));

        //Mock объект для гидратора
        /** @var PHPUnit_Framework_MockObject_MockObject|HydratorInterface $hydratorMock */
        $hydratorMock = $this->getMock(HydratorInterface::class, get_class_methods(HydratorInterface::class));
        $hydratorMock->expects(static::once())
                     ->method('hydrate')
                     ->with(static::equalTo($unserializedData), static::equalTo($message))
                     ->will(static::returnValue($message));


        //Устанавливаем необходимые для теста зависимости Message
        $message->expects(static::once())
                ->method('getHydrator')
                ->will(static::returnValue($hydratorMock));
        $message->expects(static::once())
                ->method('getValidator')
                ->will(static::returnValue($validatorMock));


        $actual = $message->fromString($serializedData);

        static::assertEquals($message, $actual);
    }



    /**
     * Тестирование заполнения сообщения из невалидной строки.
     *
     * @expectedException \OldTown\EventBus\Message\Exception\DataForMessageNotValidException
     * @expectedExceptionMessage Данные не прошли валидацию
     */
    public function testFromInvalidString()
    {
        $unserializedData = [
            'key_1' => 'value_1',
            'key_2' => 'value_2',
            'key_3' => 'value_3',
            'key_4' => 'value_4'
        ];
        $serializedData = Json::encode($unserializedData);

        //Mock объект для соощения
        /** @var PHPUnit_Framework_MockObject_MockObject|AbstractMessage  $message */
        $message = $this->getMockForAbstractClass(AbstractMessage::class);

        //Mock объект для валидатора
        /** @var PHPUnit_Framework_MockObject_MockObject|ValidatorInterface $validatorMock */
        $validatorMock = $this->getMock(ValidatorInterface::class, get_class_methods(ValidatorInterface::class));
        $validatorMock->expects(static::once())
            ->method('isValid')
            ->with(static::equalTo($unserializedData))
            ->will(static::returnValue(false));
        $validatorMock->expects(static::once())
            ->method('getMessages')
            ->will(static::returnValue('Данные не прошли валидацию'));


        //Устанавливаем необходимые для теста зависимости Message
        $message->expects(static::once())
            ->method('getValidator')
            ->will(static::returnValue($validatorMock));


        $message->fromString($serializedData);
    }
}
