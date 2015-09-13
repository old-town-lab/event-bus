<?php
/**
 * @link https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\PhpUnit\Test\Message;

use OldTown\EventBus\Validator\DelegatingValidator;
use OldTown\EventBus\Validator\DelegatingValidatorFactory;
use PHPUnit_Framework_TestCase;
use OldTown\EventBus\Message\AbstractMessage;
use PHPUnit_Framework_MockObject_MockObject;
use Zend\Serializer\Adapter\AdapterInterface as Serializer;
use Zend\Serializer\Adapter\Json as JsonSerializer;
use Zend\Stdlib\Hydrator\HydratorInterface;
use Zend\Json\Json;
use Zend\Stdlib\Hydrator\HydratorPluginManager;
use Zend\Validator\ValidatorInterface;
use Zend\Validator\ValidatorPluginManager;

/**
 * Class AbstractMessageTest
 *
 * @package OldTown\EventBus\PhpUnit\Test\Message
 */
class AbstractMessageTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var PHPUnit_Framework_MockObject_MockObject|AbstractMessage
     */
    protected $message;

    /**
     * @return void
     */
    protected function setUp()
    {
        $hydratorPluginManager = new HydratorPluginManager();
        $validatorPluginManager = new ValidatorPluginManager();
        $validatorPluginManager->setFactory(DelegatingValidator::class, new DelegatingValidatorFactory());

        /** @var PHPUnit_Framework_MockObject_MockObject|AbstractMessage  $message */
        $message = $this->getMockForAbstractClass(AbstractMessage::class, [
            'hydratorPluginManager' => $hydratorPluginManager,
            'validatorPluginManager' => $validatorPluginManager
        ]);

        $this->message = $message;
    }



    /**
     * Проверка получения/установки опций сериалайзера
     *
     */
    public function testGetterSetterSerializerOptions()
    {
        $expected = [
            'test_key_1' => 'test_value_1',
            'test_key_2' => 'test_value_2',
            'test_key_3' => 'test_value_3'
        ];

        static::assertTrue($this->message->setSerializerOptions($expected) === $this->message);

        $actual = $this->message->getSerializerOptions();

        static::assertEquals($expected, $actual);
    }


    /**
     * Проверка получения/установки опций гидратора
     *
     */
    public function testGetterSetterHydratorOptions()
    {
        $expected = [
            'test_key_1' => 'test_value_1',
            'test_key_2' => 'test_value_2',
            'test_key_3' => 'test_value_3'
        ];

        static::assertTrue($this->message->setHydratorOptions($expected) === $this->message);

        $actual = $this->message->getHydratorOptions();

        static::assertEquals($expected, $actual);
    }


    /**
     * Проверка получения/установки имени гидратора
     *
     */
    public function testGetterSetterHydratorName()
    {
        $expected = 'test_hydrator_name';

        static::assertTrue($this->message->setHydratorName($expected) === $this->message);

        $actual = $this->message->getHydratorName();

        static::assertEquals($expected, $actual);
    }

    /**
     * Проверка получения/установки имени сериалайзера
     *
     */
    public function testGetterSetterSerializerName()
    {
        $expected = 'test_serializer_name';

        static::assertTrue($this->message->setSerializerName($expected) === $this->message);

        $actual = $this->message->getSerializerName();

        static::assertEquals($expected, $actual);
    }


    /**
     * Проверка получения/установки сериалайзера
     *
     */
    public function testGetterSetterSerializer()
    {

        /** @var PHPUnit_Framework_MockObject_MockObject|Serializer $expected */
        $expected = $this->getMock(Serializer::class);

        static::assertTrue($this->message->setSerializer($expected) === $this->message);

        $actual = $this->message->getSerializer();

        static::assertEquals($expected, $actual);
    }

    /**
     * Проверка получения/установки сериалайзера
     *
     */
    public function testGetDefaultSerializer()
    {
        $actual = $this->message->getSerializer();

        static::assertInstanceOf(JsonSerializer::class, $actual);
    }



    /**
     * Проверка получения контента. В качетсе сериализатора используется стандартный - json
     *
     */
    public function testGetContent()
    {
        $extractResult = [
            'key_1' => 'value_1',
            'key_2' => 'value_2',
            'key_3' => 'value_3',
            'key_4' => 'value_4'
        ];

        /** @var PHPUnit_Framework_MockObject_MockObject|HydratorInterface $hydratorMock */
        $hydratorMock = $this->getMock(HydratorInterface::class, get_class_methods(HydratorInterface::class));
        $hydratorMock->expects(static::once())->method('extract')->will(static::returnValue($extractResult));

        $this->message->setHydrator($hydratorMock);

        $actual = $this->message->getContent();

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
                     ->with(static::equalTo($unserializedData), static::equalTo($this->message))
                     ->will(static::returnValue($this->message));


        //Устанавливаем необходимые для теста зависимости Message
        $this->message->setHydrator($hydratorMock);
        $this->message->setValidator($validatorMock);



        $actual = $this->message->setContent($serializedData);

        static::assertEquals($this->message, $actual);
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

        //Mock объект для валидатора
        /** @var PHPUnit_Framework_MockObject_MockObject|ValidatorInterface $validatorMock */
        $validatorMock = $this->getMock(ValidatorInterface::class, get_class_methods(ValidatorInterface::class));
        $validatorMock->expects(static::once())
            ->method('isValid')
            ->with(static::equalTo($unserializedData))
            ->will(static::returnValue(false));
        $validatorMock->expects(static::once())
            ->method('getMessages')
            ->will(static::returnValue(['Данные не прошли валидацию']));


        //Устанавливаем необходимые для теста зависимости Message
        $this->message->setValidator($validatorMock);



        $this->message->setContent($serializedData);
    }

    /**
     * Проверка получения/установки имени валидатора
     *
     */
    public function testGetterSetterValidatorName()
    {
        $expected = 'test_validator_name';
        static::assertEquals($this->message, $this->message->setValidatorName($expected));
        static::assertEquals($expected, $this->message->getValidatorName());
    }

    /**
     * Проверка получения/установки опций валидатора
     *
     */
    public function testGetterSetterValidatorOptions()
    {
        $expected = [
            'test_key_1' => 'test_value_1',
            'test_key_2' => 'test_value_2',
            'test_key_3' => 'test_value_3'
        ];

        static::assertEquals($this->message, $this->message->setValidatorOptions($expected));

        $actual = $this->message->getValidatorOptions();

        static::assertEquals($expected, $actual);
    }
}
