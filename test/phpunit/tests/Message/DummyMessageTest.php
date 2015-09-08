<?php
/**
 * @link https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\PhpUnit\Test\Message;

use PHPUnit_Framework_TestCase;
use OldTown\EventBus\Message\DummyMessage;


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
        $this->dummy = new DummyMessage();
    }


    /**
     * Проверка получения гидратора
     *
     */
    public function testGetHydrator()
    {
        static::assertTrue($this->dummy === $this->dummy->getHydrator());
    }


    /**
     * Проверка получения валидатора
     *
     */
    public function testGetValidator()
    {
        static::assertTrue($this->dummy === $this->dummy->getValidator());
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
     * Проверка ситуации когда гердируется некорректный объект
     *
     * @expectedException \OldTown\EventBus\Message\Exception\InvalidArgumentException
     * @expectedExceptionMessage Некорректный объект
     */
    public function testInvalidHydrateObject()
    {
        $invalidObject = new DummyMessage();
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
        $invalidObject = new DummyMessage();
        $this->dummy->extract($invalidObject);
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
}
