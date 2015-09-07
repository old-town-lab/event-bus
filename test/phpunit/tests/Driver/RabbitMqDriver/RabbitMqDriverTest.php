<?php
/**
 * @link    https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\PhpUnit\Test\Driver;

use OldTown\EventBus\Driver\DriverConfig;
use OldTown\EventBus\MetadataReader\EventBusMetadataReaderPluginManager;
use OldTown\EventBus\MetadataReader\MetadataInterface;
use OldTown\EventBus\PhpUnit\RabbitMqTestUtils\RabbitMqTestCaseInterface;
use OldTown\EventBus\PhpUnit\RabbitMqTestUtils\RabbitMqTestCaseTrait;
use PHPUnit_Framework_TestCase;
use OldTown\EventBus\Driver\RabbitMqDriver;
use OldTown\EventBus\Driver\RabbitMqDriver\Adapter\AdapterInterface;
use OldTown\EventBus\Driver\RabbitMqDriver\Adapter\AmqpPhpExtension;
use PHPUnit_Framework_MockObject_MockObject;
use OldTown\EventBus\MetadataReader\ReaderInterface;


/**
 * Class DriverChainTest
 *
 * @package OldTown\EventBus\PhpUnit\Test\Driver
 */
class RabbitMqDriverTest extends PHPUnit_Framework_TestCase implements RabbitMqTestCaseInterface
{
    use RabbitMqTestCaseTrait;

    /**
     * Получение имени адапетра
     *
     */
    public function testGetAdapterName()
    {
        $mockAdapter = $this->getMock(AdapterInterface::class);
        $mockManager = $this->getMock(EventBusMetadataReaderPluginManager::class);
        $expected = get_class($mockAdapter);

        $options = [
            'extraOptions' => [
                'adapter' => $expected
            ]
        ];
        $driver = new RabbitMqDriver($options, $mockManager);

        $actual = $driver->getAdapterName();

        static::assertEquals($expected, $actual);
    }

    /**
     * Получение имени адапетра. Обарботка ситуации когда указан несуществующий класс
     *
     * @expectedException \OldTown\EventBus\Driver\Exception\InvalidAdapterNameException
     * @expectedExceptionMessage Отсутствует класс адаптера invalid_class_name
     *
     */
    public function testGetAdapterNameInvalidClassName()
    {
        $mockManager = $this->getMock(EventBusMetadataReaderPluginManager::class);

        $options = [
            'extraOptions' => [
                'adapter' => 'invalid_class_name'
            ]
        ];
        $driver = new RabbitMqDriver($options, $mockManager);

        $driver->getAdapterName();
    }


    /**
     * Получение имени адапетра. Обработка ситуации когда указа класс не реализующий нужный интерфейс
     *
     * @expectedException \OldTown\EventBus\Driver\Exception\InvalidAdapterNameException
     * @expectedExceptionMessage Адаптер должен реализовывать OldTown\EventBus\Driver\RabbitMqDriver\Adapter\AdapterInterface
     *
     */
    public function testGetAdapterNameClassNotImplementsInterface()
    {
        $mockManager = $this->getMock(EventBusMetadataReaderPluginManager::class);

        $options = [
            'extraOptions' => [
                'adapter' => \stdClass::class
            ]
        ];
        $driver = new RabbitMqDriver($options, $mockManager);

        $driver->getAdapterName();
    }


    /**
     * Получение имени адапетра
     *
     */
    public function testGetAdapterDefaultName()
    {
        $mockManager = $this->getMock(EventBusMetadataReaderPluginManager::class);

        $options = [];
        $driver = new RabbitMqDriver($options, $mockManager);
        $actual = $driver->getAdapterName();

        static::assertEquals(AmqpPhpExtension::class, $actual);
    }

    /**
     * Получение имени адапетра. Проверка что работает локальное кеширование
     *
     */
    public function testLocalCacheGetAdapterName()
    {
        $mockManager = $this->getMock(EventBusMetadataReaderPluginManager::class);

        $options = [];
        $driver = new RabbitMqDriver($options, $mockManager);
        $expected = $driver->getAdapterName();
        $actual = $driver->getAdapterName();

        static::assertTrue($expected === $actual);
    }



    /**
     * Получение адапетра.
     *
     */
    public function testGetAdapter()
    {
        $mockAdapter = $this->getMock(AdapterInterface::class);
        $mockManager = $this->getMock(EventBusMetadataReaderPluginManager::class);
        $expected = get_class($mockAdapter);

        $options = [
            'connectionConfig' => [

            ],
            'extraOptions' => [
                'adapter' => $expected
            ]
        ];
        $driver = new RabbitMqDriver($options, $mockManager);

        $actual = $driver->getAdapter();

        static::assertInstanceOf(get_class($mockAdapter), $actual);

        //Тестирование локального кеша
        $expected = $driver->getAdapter();
        $actual = $driver->getAdapter();

        static::assertTrue($expected === $actual);
    }


    /**
     * Тестируем инициализацию шины
     *
     */
    public function testInitEventBus()
    {
        $metadataMock = $this->getMock(MetadataInterface::class);

        /** @var PHPUnit_Framework_MockObject_MockObject|ReaderInterface  $metadataReaderMock */
        $metadataReaderMock = $this->getMock(ReaderInterface::class, get_class_methods(ReaderInterface::class));
        $allClassName = [
            'test_class'
        ];
        $metadataReaderMock->expects(static::once())->method('getAllClassNames')->will(static::returnValue($allClassName));
        $metadataReaderMock->expects(static::once())
                           ->method('loadMetadataForClass')
                           ->with(static::equalTo('test_class'))
                           ->will(static::returnValue($metadataMock));

        $mockAdapter = $this->getMock(AdapterInterface::class, get_class_methods(AdapterInterface::class));
        $mockAdapter->expects(static::once())->method('initEventBus')->with(static::equalTo([
            'test_class' => $metadataMock
        ]));

        $mockManager = $this->getMock(EventBusMetadataReaderPluginManager::class);

        /** @var RabbitMqDriver|PHPUnit_Framework_MockObject_MockObject $mockDriver */
        $mockDriver = $this->getMock(RabbitMqDriver::class, [
            'getMetadataReader',
            'getAdapter'
        ], [
            'options' => [
                DriverConfig::CONNECTION_CONFIG => []
            ],
            'metadataReaderPluginManager' => $mockManager
        ]);


        $mockDriver->expects(static::once())->method('getMetadataReader')->will(static::returnValue($metadataReaderMock));
        $mockDriver->expects(static::once())->method('getAdapter')->will(static::returnValue($mockAdapter));


        $mockDriver->initEventBus();
    }
}
