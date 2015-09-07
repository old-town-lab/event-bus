<?php
/**
 * @link    https://github.com/old-town/event-buss
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\PhpUnit\Test\Driver;

use OldTown\EventBus\Driver\AbstractDriver;
use OldTown\EventBus\Driver\DriverConfig;
use PHPUnit_Framework_TestCase;
use Zend\Stdlib\Parameters;

/**
 * Class DriverChainTest
 *
 * @package OldTown\EventBus\PhpUnit\Test\Driver
 */
class AbstractDriverTest extends PHPUnit_Framework_TestCase
{
    /**
     * Создание драйвера, с пустыми опциями
     *
     */
    public function testEmptyOptions()
    {
        /** @var AbstractDriver $driver */
        $driver = $this->getMockForAbstractClass(AbstractDriver::class);

        $actual = $driver->getDriverOptions();

        static::assertEmpty($actual);
    }

    /**
     * Создание драйвера, в качетсве опций объект позволяющий работать с ним как с массивом
     *
     */
    public function testTraversableOptions()
    {
        $expected = [
          'testKey1' => 'testValue1',
          'testKey2' => 'testValue2',
        ];

        $arg = [
            'options' => new Parameters($expected)
        ];
        /** @var AbstractDriver $driver */
        $driver = $this->getMockForAbstractClass(AbstractDriver::class, $arg);

        $actual = $driver->getDriverOptions();

        static::assertEquals($expected, $actual);
    }


    /**
     * Создание драйвера, с пустыми опциями
     * @expectedException \OldTown\EventBus\Driver\Exception\InvalidArgumentException
     * @expectedExceptionMessage Некорректное значение опций
     *
     */
    public function testInvalidOptions()
    {
        $arg = [
            'options' => 'invalid-options'
        ];
        $this->getMockForAbstractClass(AbstractDriver::class, $arg);
    }

    /**
     * Создание драйвера, установка опций специфичных для конкретного драйвера
     *
     */
    public function testSetExtraOptions()
    {
        /** @var AbstractDriver $driver */
        $driver = $this->getMockForAbstractClass(AbstractDriver::class);

        $expected = [
            'test' => 'test'
        ];
        $driver->setExtraOptions($expected);
        $actual = $driver->getExtraOptions();

        static::assertEquals($expected, $actual);
    }

    /**
     * Создание драйвера, установка опций специфичных для конкретного драйвера. Установка производится через констуктор
     *
     */
    public function testSetExtraOptionsFromConfig()
    {
        $expected = [
            'test' => 'test'
        ];
        $arg = [
            'options' => [
                DriverConfig::EXTRA_OPTIONS => $expected
            ]
        ];
        /** @var AbstractDriver $driver */
        $driver = $this->getMockForAbstractClass(AbstractDriver::class, $arg);


        $driver->setExtraOptions($expected);
        $actual = $driver->getExtraOptions();

        static::assertEquals($expected, $actual);
    }
}
