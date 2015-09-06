<?php
/**
 * @link    https://github.com/old-town/event-buss
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBuss\PhpUnit\Test\Driver;

use OldTown\EventBuss\Driver\ConnectionDriverTrait;
use OldTown\EventBuss\Driver\DriverConfig;
use PHPUnit_Framework_TestCase;
use PHPUnit_Framework_MockObject_MockObject;


/**
 * Class DriverChainTest
 *
 * @package OldTown\EventBuss\PhpUnit\Test\Driver
 */
class ConnectionDriverTraitTest extends PHPUnit_Framework_TestCase
{
    /**
     * Проверка установки конфига соеденений
     *
     * @throws \PHPUnit_Framework_Exception
     */
    public function testSetConnectionConfig()
    {

        /** @var ConnectionDriverTrait $connectionDriverTrait */
        $connectionDriverTrait = $this->getMockForTrait(ConnectionDriverTrait::class);

        $expected = [
            'testKey1' => 'testValue1',
            'testKey2' => 'testValue2'
        ];
        $connectionDriverTrait->setConnectionConfig($expected);

        $actual = $connectionDriverTrait->getConnectionConfig();

        static::assertEquals($expected, $actual);
    }

    /**
     * Некорректные настройки драйвера. Отсутствует секция connectionConfig
     *
     * @expectedException \OldTown\EventBuss\Driver\Exception\InvalidEventBussDriverConfigException
     * @expectedExceptionMessage Отсутствует секция connectionConfig
     *
     * @throws \PHPUnit_Framework_Exception
     */
    public function testNotConnectionConfigInDriverOptions()
    {

        /** @var PHPUnit_Framework_MockObject_MockObject|ConnectionDriverTrait $connectionDriverTrait */
        $connectionDriverTrait = $this->getMockForTrait(ConnectionDriverTrait::class);

        $connectionDriverTrait->expects(static::once())->method('getDriverOptions')->will(static::returnValue([]));

        $connectionDriverTrait->getConnectionConfig();
    }

    /**
     * Получение connectionConfig
     *
     * @throws \PHPUnit_Framework_Exception
     */
    public function testGetConnectionConfig()
    {

        /** @var PHPUnit_Framework_MockObject_MockObject|ConnectionDriverTrait $connectionDriverTrait */
        $connectionDriverTrait = $this->getMockForTrait(ConnectionDriverTrait::class);

        $expected = [
            'testKey1' => 'testValue1',
            'testKey2' => 'testValue2'
        ];
        $connectionDriverTrait->expects(static::once())->method('getDriverOptions')->will(static::returnValue([
            DriverConfig::CONNECTION_CONFIG => $expected
        ]));

        $actual = $connectionDriverTrait->getConnectionConfig();

        static::assertEquals($expected, $actual);
    }
}