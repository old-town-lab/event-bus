<?php
/**
 * @link    https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\PhpUnit\Test\Driver\RabbitMqDriver\Adapter;

use OldTown\EventBus\Driver\RabbitMqDriver\Adapter\Exception\AmqpPhpExtensionNotInstalledException;
use OldTown\EventBus\Driver\RabbitMqDriver\MetadataReader\Metadata;
use OldTown\EventBus\PhpUnit\RabbitMqTestUtils\RabbitMqTestCaseInterface;
use OldTown\EventBus\PhpUnit\RabbitMqTestUtils\RabbitMqTestCaseTrait;
use PHPUnit_Framework_TestCase;
use OldTown\EventBus\Driver\RabbitMqDriver\Adapter\AmqpPhpExtension;
use AMQPConnection;
use AMQPChannel;
use OldTown\EventBus\Driver\RabbitMqDriver\MetadataReader\Annotations\EventBusMessage;
use OldTown\EventBus\Driver\RabbitMqDriver\MetadataReader\Annotations\Exchange;
use OldTown\EventBus\Driver\RabbitMqDriver\MetadataReader\Annotations\Queue;
use OldTown\EventBus\Driver\RabbitMqDriver\MetadataReader\Annotations\BindingKey;
use OldTown\EventBus\Message\MessageInterface;
use PHPUnit_Framework_MockObject_MockObject;

/**
 * Class AmqpPhpExtensionTest
 * @package OldTown\EventBus\PhpUnit\Test\Driver\RabbitMqDriver\Adapter
 */
class AmqpPhpExtensionTest extends PHPUnit_Framework_TestCase implements RabbitMqTestCaseInterface
{
    use RabbitMqTestCaseTrait;

    /**
     * Имя расширения
     *
     * @var string
     */
    const AMQP_EXT = 'amqp';

    /**
     * Создание адаптера
     *
     */
    public function testCreateAmqpPhpExtension()
    {
        if (!extension_loaded(self::AMQP_EXT)) {
            static::markTestSkipped(sprintf('%s extension not loaded', self::AMQP_EXT));
        }

        $adapter = new AmqpPhpExtension();

        static::assertInstanceOf(AmqpPhpExtension::class, $adapter);
    }


    /**
     * Проверка ситуации когда расширение amqp не установленно
     *
     * @expectedException \OldTown\EventBus\Driver\RabbitMqDriver\Adapter\Exception\AmqpPhpExtensionNotInstalledException
     * @expectedExceptionMessage Для работы драйвера необходимо php расширение invalid_amqp_extension
     *
     *
     */
    public function testAmqpPhpExtensionNotInstalled()
    {
        $r = new \ReflectionClass(AmqpPhpExtension::class);
        $p = $r->getProperty('amqpPhpExtensionName');
        $p->setAccessible(true);
        $original = $p->getValue(null);
        $p->setValue(null, 'invalid_amqp_extension');

        try {
            new AmqpPhpExtension();
        } catch (AmqpPhpExtensionNotInstalledException $e) {
            throw $e;
        } finally {
            $p->setValue(null, $original);
            $p->setAccessible(false);
        }
    }

    /**
     * Тестируем создание соеденения
     *
     */
    public function testGetConnection()
    {
        if (!extension_loaded(self::AMQP_EXT)) {
            static::markTestSkipped(sprintf('%s extension not loaded', self::AMQP_EXT));
        }

        $adapter = new AmqpPhpExtension();
        $connection = $adapter->getConnection();
        static::assertInstanceOf(AMQPConnection::class, $connection);


        //Тестируем локальное кеширование
        $expected = $adapter->getConnection();
        $actual = $adapter->getConnection();

        static::assertTrue($expected === $actual);
    }


    /**
     * Тестируем создание основного канала
     *
     */
    public function testGetChannel()
    {
        if (!extension_loaded(self::AMQP_EXT)) {
            static::markTestSkipped(sprintf('%s extension not loaded', self::AMQP_EXT));
        }

        $adapter = new AmqpPhpExtension([
            AmqpPhpExtension::PARAMS => $this->getRabbitMqConnectionForTest()
        ]);
        $chanel = $adapter->getChannel();
        static::assertInstanceOf(AMQPChannel::class, $chanel);


        //Тестируем локальное кеширование
        $expected = $adapter->getChannel();
        $actual = $adapter->getChannel();

        static::assertTrue($expected === $actual);
    }


    /**
     * Тестируем создание канала для инициации шины
     *
     */
    public function testGetChannelInitBus()
    {
        if (!extension_loaded(self::AMQP_EXT)) {
            static::markTestSkipped(sprintf('%s extension not loaded', self::AMQP_EXT));
        }

        $adapter = new AmqpPhpExtension([
            AmqpPhpExtension::PARAMS => $this->getRabbitMqConnectionForTest()
        ]);
        $chanel = $adapter->getChannelInitBus();
        static::assertInstanceOf(AMQPChannel::class, $chanel);


        //Тестируем локальное кеширование
        $expected = $adapter->getChannelInitBus();
        $actual = $adapter->getChannelInitBus();

        static::assertTrue($expected === $actual);
    }

    /**
     * Тестируем создание канала для инициации шины
     *
     * @expectedException \OldTown\EventBus\Driver\RabbitMqDriver\Adapter\Exception\InvalidMetadataException
     * @expectedExceptionMessage Метаданные должны реализовывать класс OldTown\EventBus\Driver\RabbitMqDriver\MetadataReader\Metadata
     */
    public function testInitEventBusMetadataError()
    {
        if (!extension_loaded(self::AMQP_EXT)) {
            static::markTestSkipped(sprintf('%s extension not loaded', self::AMQP_EXT));
        }

        $adapter = new AmqpPhpExtension([
            AmqpPhpExtension::PARAMS => $this->getRabbitMqConnectionForTest()
        ]);
        $metadata = [
            new \stdClass()
        ];
        $adapter->initEventBus($metadata);
    }


    /**
     * Тестируем функционал по созданию обменника
     *
     */
    public function testCreateExchangeByMetadata()
    {
        if (!extension_loaded(self::AMQP_EXT)) {
            static::markTestSkipped(sprintf('%s extension not loaded', self::AMQP_EXT));
        }

        $annotation = new EventBusMessage();
        $annotation->exchange = new Exchange();
        $annotation->exchange->name = 'test_exchange_name';
        $annotation->exchange->durable = true;
        $annotation->exchange->type = 'direct';

        $annotation->queue = new Queue();
        $annotation->queue->name = 'test';

        $metadata = new Metadata($annotation);


        $adapter = new AmqpPhpExtension([
            AmqpPhpExtension::PARAMS => $this->getRabbitMqConnectionForTest()
        ]);

        $adapter->initEventBus([$metadata]);

        $actualExchange = $this->getRabbitMqTestManager()->getExchange($annotation->exchange->name);
        $actual = [
            'name' => $annotation->exchange->name,
            'type'=> $annotation->exchange->type,
            'durable'=> $annotation->exchange->durable,
        ];

        static::assertEquals($actual, $actualExchange);
    }

    /**
     * Тестируем функционал по созданию обменника
     *
     * @expectedException \OldTown\EventBus\Driver\RabbitMqDriver\Adapter\Exception\InvalidExchangeTypeException
     * @expectedExceptionMessage Некорректный тип обменника invalid_type
     */
    public function testExchangeInvalidType()
    {
        if (!extension_loaded(self::AMQP_EXT)) {
            static::markTestSkipped(sprintf('%s extension not loaded', self::AMQP_EXT));
        }

        $annotation = new EventBusMessage();
        $annotation->exchange = new Exchange();
        $annotation->exchange->name = 'test_exchange_name';
        $annotation->exchange->durable = true;
        $annotation->exchange->type = 'invalid_type';

        $annotation->queue = new Queue();
        $annotation->queue->name = 'test';

        $metadata = new Metadata($annotation);


        $adapter = new AmqpPhpExtension([
            AmqpPhpExtension::PARAMS => $this->getRabbitMqConnectionForTest()
        ]);

        $adapter->initEventBus([$metadata]);
    }


    /**
     * Тестируем функционал по созданию очереди
     *
     */
    public function testCreateQueueByMetadata()
    {
        if (!extension_loaded(self::AMQP_EXT)) {
            static::markTestSkipped(sprintf('%s extension not loaded', self::AMQP_EXT));
        }

        $annotation = new EventBusMessage();
        $annotation->exchange = new Exchange();
        $annotation->exchange->name = 'test_exchange_name';
        $annotation->exchange->durable = true;
        $annotation->exchange->type = 'direct';

        $annotation->queue = new Queue();
        $annotation->queue->name = 'test_queue_name';

        $metadata = new Metadata($annotation);


        $adapter = new AmqpPhpExtension([
            AmqpPhpExtension::PARAMS => $this->getRabbitMqConnectionForTest()
        ]);

        $adapter->initEventBus([$metadata]);

        $actualExchange = $this->getRabbitMqTestManager()->getQueue($annotation->queue->name);
        $actual = [
            'name' => $annotation->queue->name,
        ];

        static::assertEquals($actual, $actualExchange);
    }



    /**
     * Тестируем функционал по созданию очереди
     *
     */
    public function testCreateBindKeysByMetadata()
    {
        if (!extension_loaded(self::AMQP_EXT)) {
            static::markTestSkipped(sprintf('%s extension not loaded', self::AMQP_EXT));
        }

        $annotation = new EventBusMessage();
        $annotation->exchange = new Exchange();
        $annotation->exchange->name = 'test_exchange_name';
        $annotation->exchange->durable = true;
        $annotation->exchange->type = 'direct';

        $annotation->queue = new Queue();
        $annotation->queue->name = 'test_queue_name';

        $expectedBindingKeys = [
            'key_1',
            'key_2',
            'key_3',
        ];

        foreach ($expectedBindingKeys as $keyName) {
            $key = new BindingKey();
            $key->name = $keyName;
            $annotation->bindingKeys[] = $key;
        }

        $metadata = new Metadata($annotation);


        $adapter = new AmqpPhpExtension([
            AmqpPhpExtension::PARAMS => $this->getRabbitMqConnectionForTest()
        ]);

        $adapter->initEventBus([$metadata]);

        $actualBindingKeys = $this->getRabbitMqTestManager()->getBindingsByExchangeAndQueue($annotation->exchange->name, $annotation->queue->name);

        static::assertEquals(count($actualBindingKeys), count($expectedBindingKeys));

        foreach ($actualBindingKeys as $actualBindingKey) {
            $actualBindingKeyName = $actualBindingKey->routing_key;
            static::assertTrue(in_array($actualBindingKeyName, $expectedBindingKeys, true));
        }
    }

    /**
     * Тест публикации сообщений
     *
     */
    public function testTrigger()
    {
        if (!extension_loaded(self::AMQP_EXT)) {
            static::markTestSkipped(sprintf('%s extension not loaded', self::AMQP_EXT));
        }

        $annotation = new EventBusMessage();
        $annotation->exchange = new Exchange();
        $annotation->exchange->name = 'test_exchange_name';
        $annotation->exchange->durable = true;
        $annotation->exchange->type = 'direct';

        $annotation->queue = new Queue();
        $annotation->queue->name = 'test_queue_name';

        $bindKey = new BindingKey();
        $bindKey->name = 'test_queue_name';

        $annotation->bindingKeys[] = $bindKey;


        $metadata = new Metadata($annotation);

        $adapter = new AmqpPhpExtension([
            AmqpPhpExtension::PARAMS => $this->getRabbitMqConnectionForTest()
        ]);

        $expected = 'test_content';

        /** @var PHPUnit_Framework_MockObject_MockObject|MessageInterface $message */
        $message = $this->getMock(MessageInterface::class, get_class_methods(MessageInterface::class));
        $message->expects(static::once())->method('getContent')->will(static::returnValue($expected));

        $adapter->initEventBus([$metadata]);
        $adapter->trigger('test_queue_name', $message, $metadata);


        $actualMessages = $this->getRabbitMqTestManager()->readMessagesFromQueue($annotation->queue->name);

        static::assertEquals(1, count($actualMessages));

        /** @var \AMQPEnvelope $actualMessage */
        $actualMessage = array_pop($actualMessages);

        static::assertEquals($expected, $actualMessage->getBody());
    }
}
