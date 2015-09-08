<?php
/**
 * @link    https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\PhpUnit\Test\Driver\RabbitMqDriver\Adapter;

use PHPUnit_Framework_TestCase;
use \OldTown\EventBus\Driver\RabbitMqDriver\Adapter\AbstractAdapter;
use PHPUnit_Framework_MockObject_MockObject;


/**
 * Class AbstractAdapterTest
 * @package OldTown\EventBus\PhpUnit\Test\Driver\RabbitMqDriver\Adapter
 */
class AbstractAdapterTest extends PHPUnit_Framework_TestCase
{
    /**
     * @return array
     */
    public function testGetConnectionConfig()
    {
        $expected = [
            'test_key' => 'test_value'
        ];

        /** @var PHPUnit_Framework_MockObject_MockObject|AbstractAdapter $adapter */
        $adapter = $this->getMockForAbstractClass(AbstractAdapter::class, [
            'connection' => $expected
        ]);

        $actual = $adapter->getConnectionConfig();

        static::assertEquals($expected, $actual);
    }
}
