<?php
/**
 * @link https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\Driver\RabbitMqDriver\Adapter\AmqpPhpExtension;

use AMQPEnvelope;
use AMQPQueue;

class RawArgument
{
    /**
     * @var AMQPEnvelope
     */
    protected $rawMessage;

    /**
     * @var AMQPQueue
     */
    protected $rawQueue;

    /**
     * @param AMQPEnvelope $rawMessage
     * @param AMQPQueue    $rawQueue
     */
    public function __construct(AMQPEnvelope $rawMessage, AMQPQueue $rawQueue)
    {
        $this->rawMessage = $rawMessage;
        $this->rawQueue = $rawQueue;
    }

    /**
     * @param array $data
     *
     * @return RawArgument
     *
     * @throws \OldTown\EventBus\Driver\RabbitMqDriver\Adapter\AmqpPhpExtension\Exception\InvalidRawArgumentException
     */
    public static function factory(array $data = [])
    {
        if (!array_key_exists(0, $data) || !$data[0] instanceof AMQPEnvelope) {
            $errMsg = 'Некорретнай формат аргументов. Первый элемент должен быть AMQPEnvelope';
            throw new Exception\InvalidRawArgumentException($errMsg);
        }
        if (!array_key_exists(1, $data) || !$data[1] instanceof AMQPQueue) {
            $errMsg = 'Некорретнай формат аргументов. Второй элемент должен быть AMQPQueue';
            throw new Exception\InvalidRawArgumentException($errMsg);
        }

        $rawArgument = new static($data[0], $data[1]);

        return $rawArgument;
    }

    /**
     * @return AMQPEnvelope
     */
    public function getRawMessage()
    {
        return $this->rawMessage;
    }

    /**
     * @return AMQPQueue
     */
    public function getRawQueue()
    {
        return $this->rawQueue;
    }
}
