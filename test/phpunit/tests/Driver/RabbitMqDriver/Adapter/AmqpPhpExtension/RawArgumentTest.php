<?php
/**
 * @link    https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\PhpUnit\Test\Driver\RabbitMqDriver\Adapter\AmqpPhpExtension;

use OldTown\EventBus\PhpUnit\RabbitMqTestUtils\RabbitMqTestCaseInterface;
use OldTown\EventBus\PhpUnit\RabbitMqTestUtils\RabbitMqTestCaseTrait;
use PHPUnit_Framework_TestCase;
use OldTown\EventBus\Driver\RabbitMqDriver\Adapter\AmqpPhpExtension\RawArgument;

/**
 * Class RawArgumentTest
 *
 * @package OldTown\EventBus\PhpUnit\Test\Driver\RabbitMqDriver\Adapter\AmqpPhpExtension
 */
class RawArgumentTest extends PHPUnit_Framework_TestCase  implements RabbitMqTestCaseInterface
{
    use RabbitMqTestCaseTrait;

    /**
     * Проверка ситуации когда в качестве аргумента для фабрики передали не валидный массив. Некорректный первый элемент.
     *
     * @expectedException \OldTown\EventBus\Driver\RabbitMqDriver\Adapter\AmqpPhpExtension\Exception\InvalidRawArgumentException
     * @expectedExceptionMessage Некорретнай формат аргументов. Первый элемент должен быть AMQPEnvelope
     */
    public function testFactoryInvalidAMQPEnvelope()
    {
        $data = [
            0 => new \stdClass()
        ];
        RawArgument::factory($data);
    }

    /**
     * Проверка ситуации когда в качестве аргумента для фабрики передали не валидный массив. Некорректный второй элемент.
     *
     * @expectedException \OldTown\EventBus\Driver\RabbitMqDriver\Adapter\AmqpPhpExtension\Exception\InvalidRawArgumentException
     * @expectedExceptionMessage Некорретнай формат аргументов. Второй элемент должен быть AMQPQueue
     */
    public function testFactoryInvalidAMQPQueue()
    {
        $data = [
            0 => new \AMQPEnvelope(),
            1 => new \stdClass()
        ];
        RawArgument::factory($data);
    }


    /**
     * Проверка работы фабрики

     */
    public function testFactory()
    {
        $expectedAMQPEnvelope = new \AMQPEnvelope();
        $connectParams = $this->getRabbitMqConnectionForTest();
        $connection = new \AMQPConnection($connectParams);
        $connection->connect();
        $chanel = new \AMQPChannel($connection);
        $expectedAMQPQueue = new \AMQPQueue($chanel);
        $data = [
            0 => $expectedAMQPEnvelope,
            1 => $expectedAMQPQueue
        ];
        $actual = RawArgument::factory($data);
        static::assertInstanceOf(RawArgument::class, $actual);

        static::assertEquals($expectedAMQPEnvelope, $actual->getRawMessage());
        static::assertEquals($expectedAMQPQueue, $actual->getRawQueue());
    }
}
