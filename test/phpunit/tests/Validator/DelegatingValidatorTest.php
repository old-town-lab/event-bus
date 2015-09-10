<?php
/**
 * @link https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\PhpUnit\Test\Validator;

use PHPUnit_Framework_TestCase;
use OldTown\EventBus\Validator\DelegatingValidator;


/**
 * Class DelegatingValidatorTest
 * @package OldTown\EventBus\PhpUnit\Test\Validator
 */
class DelegatingValidatorTest extends PHPUnit_Framework_TestCase
{
    /**
     * Создание DelegatingValidator
     */
    public function testCreateDelegatingValidator()
    {
        $expectedDelegateObject = $this->getMock(DelegatingValidator::class);
        $options = [
            DelegatingValidator::DELEGATE_OBJECT => $expectedDelegateObject
        ];

        $validator = new DelegatingValidator($options);

        static::assertInstanceOf(DelegatingValidator::class, $validator);
        static::assertEquals($expectedDelegateObject, $validator->getDelegateObject());
    }

    /**
     * Создание установки/получения DelegateObject
     */
    public function testGetterSetterDelegateObject()
    {
        /** @var DelegatingValidator $expectedDelegateObject */
        $expectedDelegateObject = $this->getMock(DelegatingValidator::class);
        $validator = new DelegatingValidator();


        static::assertEquals($validator, $validator->setDelegateObject($expectedDelegateObject));
        static::assertEquals($expectedDelegateObject, $validator->getDelegateObject());
    }


    /**
     * Создание установки/получения DelegateObject
     *
     * @expectedException \OldTown\EventBus\Validator\Exception\DelegateObjectNotFoundException
     * @expectedExceptionMessage Отсутствует объект для делегирования
     */
    public function testInvalidDelegateObject()
    {
        $validator = new DelegatingValidator();
        $validator->getDelegateObject();
    }


    /**
     * Проверка валидации
     *
     */
    public function testIsValid()
    {
        $validData = 'valid-data';
        $expected = true;


        $expectedDelegateObject = $this->getMock(DelegatingValidator::class, get_class_methods(DelegatingValidator::class));
        $expectedDelegateObject->expects(static::once())
                               ->method('isValid')
                               ->with(static::equalTo($validData))
                               ->will(static::returnValue($expected));

        $options = [
            DelegatingValidator::DELEGATE_OBJECT => $expectedDelegateObject
        ];

        $validator = new DelegatingValidator($options);

        $actual = $validator->isValid($validData);

        static::assertEquals($expected, $actual);
    }


    /**
     * Проверка получения сообщений о ошибках
     *
     */
    public function testGetMessages()
    {
        $expected = [
            'test_error_message_1',
            'test_error_message_2',
            'test_error_message_3'
        ];


        $expectedDelegateObject = $this->getMock(DelegatingValidator::class, get_class_methods(DelegatingValidator::class));
        $expectedDelegateObject->expects(static::once())
            ->method('getMessages')
            ->will(static::returnValue($expected));

        $options = [
            DelegatingValidator::DELEGATE_OBJECT => $expectedDelegateObject
        ];

        $validator = new DelegatingValidator($options);

        $actual = $validator->getMessages();

        static::assertEquals($expected, $actual);
    }
}
