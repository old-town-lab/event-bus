<?php
/**
 * @link    https://github.com/old-town/event-buss
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBuss\PhpUnitTest\Driver;

use OldTown\EventBuss\Driver\DriverConfig;
use OldTown\EventBuss\Driver\MetadataReaderTrait;
use OldTown\EventBuss\MetadataReader\EventBussMetadataReaderPluginManager;
use OldTown\EventBuss\MetadataReader\ReaderInterface;
use PHPUnit_Framework_TestCase;
use PHPUnit_Framework_MockObject_MockObject;

/**
 * Class MetadataReaderTraitTest
 *
 * @package OldTown\EventBuss\PhpUnitTest\Driver
 */
class MetadataReaderTraitTest extends PHPUnit_Framework_TestCase
{
    /**
     * Данные для тестирования метода getPath
     *
     * @var array
     */
    protected $testGetPathData = [
        [
            'paths' => [
                'path_to_dir_1',
                'path_to_dir_2'
            ],
            'driverOptions' => [
                DriverConfig::PATHS => [
                    'path_to_dir_1',
                    'path_to_dir_2'
                ]
            ]

        ],
        [
            'paths' => [],
            'driverOptions' => []

        ]
    ];

    /**
     * @return array
     */
    public function testGetPathDataProvider()
    {
        return $this->testGetPathData;
    }



    /**
     * Тестируем получение путей на основе конфига
     *
     * @dataProvider testGetPathDataProvider
     *
     * @param array $driverOptions
     * @param array $paths
     */
    public function testGetPaths(array $paths, array $driverOptions)
    {
        /** @var PHPUnit_Framework_MockObject_MockObject|MetadataReaderTrait $metadataReaderTraitMock */
        $metadataReaderTraitMock = static::getMockForTrait(MetadataReaderTrait::class);

        $metadataReaderTraitMock->expects(static::once())->method('getDriverOptions')->will(static::returnValue($driverOptions));

        $actual = $metadataReaderTraitMock->getPaths();

        static::assertEquals($paths, $actual);
    }

    /**
     * Проверка getter/setter для свойства path
     *
     * @dataProvider testGetPathDataProvider
     *
     * @param array $paths
     */
    public function testGetterSetterPath(array $paths)
    {
        /** @var PHPUnit_Framework_MockObject_MockObject|MetadataReaderTrait $metadataReaderTraitMock */
        $metadataReaderTraitMock = static::getMockForTrait(MetadataReaderTrait::class);

        $metadataReaderTraitMock->setPaths($paths);
        $actual = $metadataReaderTraitMock->getPaths();

        static::assertEquals($paths, $actual);
    }


    /**
     * Проверка getter/setter для свойства metadataReaderPluginManager
     */
    public function testGetterSetterMetadataReaderPluginManager()
    {
        /** @var PHPUnit_Framework_MockObject_MockObject|MetadataReaderTrait $metadataReaderTraitMock */
        $metadataReaderTraitMock = static::getMockForTrait(MetadataReaderTrait::class);

        /** @var EventBussMetadataReaderPluginManager $metadataReaderPluginManager */
        $expected = $metadataReaderPluginManager = static::getMock(EventBussMetadataReaderPluginManager::class);

        $metadataReaderTraitMock->setMetadataReaderPluginManager($metadataReaderPluginManager);
        $actual = $metadataReaderTraitMock->getMetadataReaderPluginManager();

        static::assertEquals($expected, $actual);
    }

    /**
     * Получаем имя стандартного MetadataReader
     *
     */
    public function testGetDefaultMetadataReaderName()
    {
        /** @var PHPUnit_Framework_MockObject_MockObject|MetadataReaderTrait $metadataReaderTraitMock */
        $metadataReaderTraitMock = static::getMockForTrait(MetadataReaderTrait::class);

        $metadataReaderTraitMock->expects(static::once())->method('getDriverOptions')->will(static::returnValue([]));

        $expected = 'default_metadata_reader_name';
        $property = 'defaultMetadataReaderName';
        $metadataReaderTraitMock->{$property} = $expected;


        $actual = $metadataReaderTraitMock->getMetadataReaderName();

        static::assertEquals($expected, $actual);
    }

    /**
     * Получаем имя  MetadataReader из настроек драйвера
     *
     */
    public function testGetMetadataReaderNameFromDriverOptions()
    {
        /** @var PHPUnit_Framework_MockObject_MockObject|MetadataReaderTrait $metadataReaderTraitMock */
        $metadataReaderTraitMock = static::getMockForTrait(MetadataReaderTrait::class);

        $expected = 'metadata_reader_name_from_driver_options';

        $metadataReaderTraitMock->expects(static::once())->method('getDriverOptions')->will(static::returnValue([
            DriverConfig::METADATA_READER => $expected
        ]));
        $actual = $metadataReaderTraitMock->getMetadataReaderName();

        static::assertEquals($expected, $actual);


        /**
         * Проверка локального кеширования
         */
        $expectedMetadataReaderName = $metadataReaderTraitMock->getMetadataReaderName();
        $actualMetadataReaderName = $metadataReaderTraitMock->getMetadataReaderName();
        static::assertTrue($expectedMetadataReaderName === $actualMetadataReaderName);
    }


    /**
     * Получаем имя  MetadataReader из настроек драйвера
     *
     * @expectedException \OldTown\EventBuss\Driver\Exception\InvalidMetadataReaderNameException
     * @expectedExceptionMessage Некорректное значение опций
     */
    public function testGetDefaultMetadataReaderNameInvalid()
    {
        /** @var PHPUnit_Framework_MockObject_MockObject|MetadataReaderTrait $metadataReaderTraitMock */
        $metadataReaderTraitMock = static::getMockForTrait(MetadataReaderTrait::class);
        $metadataReaderTraitMock->expects(static::once())->method('getDriverOptions')->will(static::returnValue([]));
        $metadataReaderTraitMock->getMetadataReaderName();
    }

    /**
     * Получение объекта MetadataReader
     *
     */
    public function testGetMetadataReader()
    {
        $metadataReaderName = 'metadataReader';

        /** @var EventBussMetadataReaderPluginManager $metadataReaderPluginManager */
        $metadataReaderPluginManager = new EventBussMetadataReaderPluginManager();
        $expectedMetadataReader = static::getMock(ReaderInterface::class);
        $metadataReaderPluginManager->setService($metadataReaderName, $expectedMetadataReader);

        /** @var PHPUnit_Framework_MockObject_MockObject|MetadataReaderTrait $metadataReaderTraitMock */
        $metadataReaderTraitMock = static::getMockForTrait(MetadataReaderTrait::class);
        $metadataReaderTraitMock->setMetadataReaderPluginManager($metadataReaderPluginManager);
        $metadataReaderTraitMock->expects(static::any())->method('getDriverOptions')->will(static::returnValue([
            DriverConfig::METADATA_READER => $metadataReaderName
        ]));

        $actualMetadataReader = $metadataReaderTraitMock->getMetadataReader();

        static::assertTrue($expectedMetadataReader === $actualMetadataReader);

        /**
         * Проверка локального кеширования
         */
        $expectedMetadataReader = $metadataReaderTraitMock->getMetadataReader();
        $actualMetadataReader = $metadataReaderTraitMock->getMetadataReader();
        static::assertTrue($expectedMetadataReader === $actualMetadataReader);
    }
}
