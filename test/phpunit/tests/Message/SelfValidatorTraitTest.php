<?php
/**
 * @link https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\PhpUnit\Test\Message;

use PHPUnit_Framework_TestCase;
use OldTown\EventBus\Message\SelfValidatorTrait;
use Zend\Validator\ValidatorInterface;

/**
 * Class SelfValidatorTraitTest
 *
 * @package OldTown\EventBus\PhpUnit\Test\Message
 */
class SelfValidatorTraitTest extends PHPUnit_Framework_TestCase
{
    /**
     * Проврека установки/получения объекта валидатора
     *
     */
    public function testGetterSetterValidator()
    {
        /** @var SelfValidatorTrait $mockMessage */
        $mockMessage = $this->getMockForTrait(SelfValidatorTrait::class);
        /** @var ValidatorInterface $mockValidator */
        $mockValidator = $this->getMock(ValidatorInterface::class);

        static::assertTrue($mockMessage->setValidator($mockValidator) === $mockMessage);

        static::assertTrue($mockValidator === $mockMessage->getValidator());
    }

    /**
     * Проверка ситуации когда сообщение не реализует интерфейс валидатора
     *
     * @expectedException \OldTown\EventBus\Message\Exception\InvalidValidatorException
     * @expectedExceptionMessage Сообщение должно имплементировать Zend\Validator\ValidatorInterface
     *
     */
    public function testMessageNotImplementsValidatorInterface()
    {
        /** @var SelfValidatorTrait $mockMessage */
        $mockMessage = $this->getMockForTrait(SelfValidatorTrait::class);

        $mockMessage->getValidator();
    }
}
