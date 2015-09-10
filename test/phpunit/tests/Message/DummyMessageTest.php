<?php
/**
 * @link https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\PhpUnit\Test\Message;

use PHPUnit_Framework_TestCase;
use OldTown\EventBus\Message\DummyMessage;
use Zend\Stdlib\Hydrator\HydratorPluginManager;
use Zend\Validator\ValidatorPluginManager;
use OldTown\EventBus\Validator\DelegatingValidator;
use Zend\Json\Json;
use OldTown\EventBus\Hydrator\DelegatingHydrator;

/**
 * Class DummyMessageTest
 *
 * @package OldTown\EventBus\PhpUnit\Test\Message
 */
class DummyMessageTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var DummyMessage
     */
    protected $dummy;

    /**
     *
     * @return void
     */
    protected function setUp()
    {
        $hydratorPluginManager = new HydratorPluginManager();
        $validatorPluginManager = new ValidatorPluginManager();

        $this->dummy = new DummyMessage($hydratorPluginManager, $validatorPluginManager);
    }


    /**
     * Проверка получения гидратора
     *
     */
    public function testGetHydrator()
    {
        static::assertInstanceOf(DelegatingHydrator::class, $this->dummy->getHydrator());
    }


    /**
     * Проверка получения валидатора
     *
     */
    public function testGetValidator()
    {
        static::assertInstanceOf(DelegatingValidator::class, $this->dummy->getValidator());
    }

    /**
     * Проверка установки/получения данных
     *
     */
    public function testGetterSetterData()
    {
        $expected = [
            'test_key_1' => 'test_value_1',
            'test_key_2' => 'test_value_2'
        ];

        static::assertTrue($this->dummy === $this->dummy->setData($expected));

        static::assertEquals($expected, $this->dummy->getData());
    }



    /**
     * Извлечение из сообщения данных для сериализации
     *
     */
    public function testExtract()
    {
        $expected = [
            'test_key_1' => 'test_value_1',
            'test_key_2' => 'test_value_2'
        ];
        $this->dummy->setData($expected);
        $actual = $this->dummy->extract($this->dummy);

        static::assertEquals($expected, $actual);
    }


    /**
     * Установка в объект данных
     *
     */
    public function testHydrate()
    {
        $expected = [
            'test_key_1' => 'test_value_1',
            'test_key_2' => 'test_value_2'
        ];

        $actual = $this->dummy->hydrate($expected, $this->dummy)->getData();

        static::assertEquals($expected, $actual);
    }


    /**
     * Проверка валидации
     *
     */
    public function testIsValid()
    {
        static::assertTrue($this->dummy->isValid([]));
    }


    /**
     * Проверка сообщения о ошибке валидации
     *
     */
    public function testGetMessages()
    {
        static::assertEmpty($this->dummy->getMessages());
    }



    /**
     * Проверка ситуации когда гердируется некорректный объект
     *
     * @expectedException \OldTown\EventBus\Message\Exception\InvalidArgumentException
     * @expectedExceptionMessage Некорректный объект
     */
    public function testInvalidHydrateObject()
    {
        /** @var DummyMessage $invalidObject */
        $invalidObject = new \stdClass();
        $this->dummy->hydrate([], $invalidObject);
    }



    /**
     * Проверка ситуации когда екстрактиться некорректный объект
     *
     * @expectedException \OldTown\EventBus\Message\Exception\InvalidArgumentException
     * @expectedExceptionMessage Некорректный объект
     */
    public function testInvalidExtractObject()
    {
        /** @var DummyMessage $invalidObject */
        $invalidObject = new \stdClass();
        $this->dummy->extract($invalidObject);
    }



    /**
     * Проверка получения контента
     *
     */
    public function testGetContent()
    {
        $data = [
            'test_key_1' => 'test_value_1',
            'test_key_2' => 'test_value_2'
        ];
        $this->dummy->setData($data);
        $content = $this->dummy->getContent();

        static::assertEquals(Json::encode($data), $content);
    }


    /**
     * Проверка востоновления состояния объекта сообщения, по десерилазованным данным
     *
     */
    public function testFromString()
    {
        $expected = [
            'test_key_1' => 'test_value_1',
            'test_key_2' => 'test_value_2'
        ];
        $serializedData = Json::encode($expected);

        $actual = $this->dummy->fromString($serializedData)->getData();
        static::assertEquals($expected, $actual);
    }
}
