<?php
/**
 * @link https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\PhpUnit\Test\Message;

use PHPUnit_Framework_TestCase;
use OldTown\EventBus\Message\SelfHydratorTrait;
use Zend\Stdlib\Hydrator\HydratorInterface;

/**
 * Class SelfHydratorTraitTest
 *
 * @package OldTown\EventBus\PhpUnit\Test\Message
 */
class SelfHydratorTraitTest extends PHPUnit_Framework_TestCase
{
    /**
     * Проврека установки/получения объекта гидратора
     *
     */
    public function testGetterSetterHydrator()
    {
        /** @var SelfHydratorTrait $mockMessage */
        $mockMessage = $this->getMockForTrait(SelfHydratorTrait::class);
        /** @var HydratorInterface $mockHydrator */
        $mockHydrator = $this->getMock(HydratorInterface::class);

        static::assertTrue($mockMessage->setHydrator($mockHydrator) === $mockMessage);

        static::assertTrue($mockHydrator === $mockMessage->getHydrator());
    }

    /**
     * Проверка ситуации когда сообщение не реализует интерфейс гидратора
     *
     * @expectedException \OldTown\EventBus\Message\Exception\InvalidHydratorException
     * @expectedExceptionMessage Сообщение должно имплементировать Zend\Stdlib\Hydrator\HydratorInterface
     *
     */
    public function testMessageNotImplementsValidatorInterface()
    {
        /** @var SelfHydratorTrait $mockMessage */
        $mockMessage = $this->getMockForTrait(SelfHydratorTrait::class);

        $mockMessage->getHydrator();
    }
}
