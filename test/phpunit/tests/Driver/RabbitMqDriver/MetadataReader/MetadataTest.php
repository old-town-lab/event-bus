<?php
/**
 * @link    https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\PhpUnit\Test\Driver\RabbitMqDriver\MetadataReader;

use PHPUnit_Framework_TestCase;
use OldTown\EventBus\Driver\RabbitMqDriver\MetadataReader\Metadata;
use OldTown\EventBus\Driver\RabbitMqDriver\MetadataReader\Annotations\EventBusMessage;
use OldTown\EventBus\Driver\RabbitMqDriver\MetadataReader\Annotations\Queue;
use OldTown\EventBus\Driver\RabbitMqDriver\MetadataReader\Annotations\Exchange;
use OldTown\EventBus\Driver\RabbitMqDriver\MetadataReader\Annotations\BindingKey;

/**
 * Class DriverChainTest
 *
 * @package OldTown\EventBus\PhpUnit\Test\Driver
 */
class MetadataTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Metadata
     */
    protected $metadata;

    /**
     *
     * @return void
     */
    protected function setUp()
    {
        $message = new EventBusMessage();
        $message->queue = new Queue();
        $message->exchange = new Exchange();

        $this->metadata = new Metadata($message);
    }

    /**
     * Проверка getter/setter для свойства queueName
     *
     */
    public function testGetterSetterQueueName()
    {
        $expected = 'test_queue_name';
        $this->metadata->setQueueName($expected);

        $actual = $this->metadata->getQueueName();

        static::assertEquals($expected, $actual);
    }


    /**
     * Проверка getter/setter для свойства queueName
     *
     */
    public function testGetterSetterExchangeName()
    {
        $expected = 'test_exchange_name';
        $this->metadata->setExchangeName($expected);

        $actual = $this->metadata->getExchangeName();

        static::assertEquals($expected, $actual);
    }


    /**
     * Проверка getter/setter для свойства queueName
     *
     */
    public function testGetterSetterBindingKeys()
    {
        $expected = [
            'binding_key_1',
            'binding_key_2',
        ];
        $this->metadata->setBindingKeys($expected);

        $actual = $this->metadata->getBindingKeys();

        static::assertEquals($expected, $actual);
    }

    /**
     * Тестирование создания метаданных на основе анотации
     *
     */
    public function testCreateMetadata()
    {
        $expectedQueueName = 'test_queue_name';
        $expectedExchangeName = 'test_exchange_name';
        $expectedExchangeDurable = true;
        $expectedBindingKeys = [
            'test_binding_key_1' => 'test_binding_key_1',
            'test_binding_key_2' => 'test_binding_key_2',
        ];


        $message = new EventBusMessage();
        $message->queue = new Queue();
        $message->queue->name = $expectedQueueName;
        $message->exchange = new Exchange();
        $message->exchange->name = $expectedExchangeName;
        $message->exchange->durable = $expectedExchangeDurable;

        $bindingKey1 = new BindingKey();
        $bindingKey1->name = 'test_binding_key_1';
        $message->bindingKeys[] = $bindingKey1;


        $bindingKey2 = new BindingKey();
        $bindingKey2->name = 'test_binding_key_2';
        $message->bindingKeys[] = $bindingKey2;

        $metadata = new Metadata($message);

        static::assertEquals($expectedQueueName, $metadata->getQueueName());
        static::assertEquals($expectedExchangeName, $metadata->getExchangeName());
        static::assertEquals($expectedBindingKeys, $metadata->getBindingKeys());
        static::assertEquals($expectedExchangeDurable, $metadata->getFlagExchangeDurable());
    }
}
