<?php
/**
 * @link    https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\PhpUnit\Test\Driver\RabbitMqDriver\Adapter;

use OldTown\EventBus\Driver\RabbitMqDriver\Adapter\Exception\AmqpPhpExtensionNotInstalledException;
use OldTown\EventBus\PhpUnit\RabbitMqTestUtils\RabbitMqTestCaseInterface;
use OldTown\EventBus\PhpUnit\RabbitMqTestUtils\RabbitMqTestCaseTrait;
use PHPUnit_Framework_TestCase;
use OldTown\EventBus\Driver\RabbitMqDriver\Adapter\AmqpPhpExtension;
use AMQPConnection;
use AMQPChannel;

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
            $this->markTestSkipped(sprintf('%s extension not loaded', self::AMQP_EXT));
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
            $this->markTestSkipped(sprintf('%s extension not loaded', self::AMQP_EXT));
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
            $this->markTestSkipped(sprintf('%s extension not loaded', self::AMQP_EXT));
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
}
