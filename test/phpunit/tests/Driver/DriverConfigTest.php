<?php
/**
 * @link    https://github.com/old-town/event-buss
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBuss\PhpUnit\Test\Driver;

use OldTown\EventBuss\Driver\DriverConfig;
use PHPUnit_Framework_TestCase;


/**
 * Class DriverConfigTest
 *
 * @package OldTown\EventBuss\PhpUnit\Test\Driver
 */
class DriverConfigTest extends PHPUnit_Framework_TestCase
{
    /**
     * Создание DriverConfig
     */
    public function testCreateDriverConfig()
    {
        $options = [
            'pluginName' => 'test'
        ];
        $driverConfig = new DriverConfig($options);

        static::assertInstanceOf(DriverConfig::class, $driverConfig);
    }

    /**
     * @expectedException \OldTown\EventBuss\Driver\Exception\InvalidEventBussDriverConfigException
     * @expectedExceptionMessage Отсутствует секция pluginName
     *
     * Создание DriverConfig. Не указан pluginNmae
     */
    public function testCreateDriverConfigPluginNameNotSpecified()
    {
        new DriverConfig();
    }

    /**
     * Проверка работы getter/setter для свойтсва pluginName
     *
     */
    public function testGetterPluginName()
    {
        $expectedPluginName = 'test';
        $options = [
            'pluginName' => $expectedPluginName
        ];
        $driverConfig = new DriverConfig($options);

        $actualPluginName = $driverConfig->getPluginName();

        static::assertEquals($expectedPluginName, $actualPluginName);
    }

    /**
     * Проверка генерации конфига
     *
     */
    public function testGetPluginConfig()
    {
        $options = [
            DriverConfig::PLUGIN_NAME       => 'test',
            DriverConfig::DRIVERS           => [
                'test'
            ],
            DriverConfig::CONNECTION        => 'test-connection-name',
            DriverConfig::CONNECTION_CONFIG => [
                'param' => [
                    'test' => 'test'
                ]
            ],
            'example' => [
                'test' => 'test'
            ],
            DriverConfig::METADATA_READER => 'test-metadata0reader',
            DriverConfig::PATHS => [
                __DIR__
            ]
        ];
        $driverConfig = new DriverConfig($options);

        $actualPluginConfig = $driverConfig->getPluginConfig();
        $expectedPluginConfig = [
            DriverConfig::DRIVERS           => [
                'test'
            ],
            DriverConfig::CONNECTION        => 'test-connection-name',
            DriverConfig::CONNECTION_CONFIG => [
                'param' => [
                    'test' => 'test'
                ]
            ],
            DriverConfig::EXTRA_OPTIONS => [
                'example' => [
                    'test' => 'test'
                ]
            ],
            DriverConfig::METADATA_READER => 'test-metadata0reader',
            DriverConfig::PATHS => [
                __DIR__
            ]
        ];

        static::assertEquals($expectedPluginConfig, $actualPluginConfig);
    }


    /**
     * Проверка генерации конфига
     *
     * @expectedException \OldTown\EventBuss\Driver\Exception\InvalidArgumentException
     * @expectedExceptionMessage Имя соеденения должно быть строкой
     */
    public function testSetConnectionNotString()
    {
        $options = [
            DriverConfig::PLUGIN_NAME       => 'test',
            DriverConfig::CONNECTION        => []
        ];
        new DriverConfig($options);
    }
}
