<?php
/**
 * @link    https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\PhpUnit\Test\Driver;

use OldTown\EventBus\Driver\DriverConfig;
use OldTown\EventBus\Driver\MessageHandler;
use OldTown\EventBus\Message\EventBusMessagePluginManager;
use OldTown\EventBus\Message\MessageInterface;
use OldTown\EventBus\MetadataReader\EventBusMetadataReaderPluginManager;
use OldTown\EventBus\PhpUnit\RabbitMqTestUtils\RabbitMqTestCaseInterface;
use OldTown\EventBus\PhpUnit\RabbitMqTestUtils\RabbitMqTestCaseTrait;
use PHPUnit_Framework_TestCase;
use OldTown\EventBus\Driver\RabbitMqDriver;
use OldTown\EventBus\Driver\RabbitMqDriver\Adapter\AdapterInterface;
use OldTown\EventBus\Driver\RabbitMqDriver\Adapter\AmqpPhpExtension;
use PHPUnit_Framework_MockObject_MockObject;
use OldTown\EventBus\MetadataReader\ReaderInterface;
use OldTown\EventBus\Driver\RabbitMqDriver\MetadataReader\MetadataInterface;


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
        $mockMetadataPluginManager = $this->getMock(EventBusMetadataReaderPluginManager::class);
        $mockMessagePluginManager = $this->getMock(EventBusMessagePluginManager::class);
        $expected = get_class($mockAdapter);

        $options = [
            'extraOptions' => [
                'adapter' => $expected
            ]
        ];
        $driver = new RabbitMqDriver($options, $mockMetadataPluginManager, $mockMessagePluginManager);

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
        $mockMetadataPluginManager = $this->getMock(EventBusMetadataReaderPluginManager::class);
        $mockMessagePluginManager = $this->getMock(EventBusMessagePluginManager::class);

        $options = [
            'extraOptions' => [
                'adapter' => 'invalid_class_name'
            ]
        ];
        $driver = new RabbitMqDriver($options, $mockMetadataPluginManager, $mockMessagePluginManager);

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
        $mockMetadataPluginManager = $this->getMock(EventBusMetadataReaderPluginManager::class);
        $mockMessagePluginManager = $this->getMock(EventBusMessagePluginManager::class);

        $options = [
            'extraOptions' => [
                'adapter' => \stdClass::class
            ]
        ];
        $driver = new RabbitMqDriver($options, $mockMetadataPluginManager, $mockMessagePluginManager);

        $driver->getAdapterName();
    }


    /**
     * Получение имени адапетра
     *
     */
    public function testGetAdapterDefaultName()
    {
        $mockMetadataPluginManager = $this->getMock(EventBusMetadataReaderPluginManager::class);
        $mockMessagePluginManager = $this->getMock(EventBusMessagePluginManager::class);

        $options = [];
        $driver = new RabbitMqDriver($options, $mockMetadataPluginManager, $mockMessagePluginManager);
        $actual = $driver->getAdapterName();

        static::assertEquals(AmqpPhpExtension::class, $actual);
    }

    /**
     * Получение имени адапетра. Проверка что работает локальное кеширование
     *
     */
    public function testLocalCacheGetAdapterName()
    {
        $mockMetadataPluginManager = $this->getMock(EventBusMetadataReaderPluginManager::class);
        $mockMessagePluginManager = $this->getMock(EventBusMessagePluginManager::class);

        $options = [];
        $driver = new RabbitMqDriver($options, $mockMetadataPluginManager, $mockMessagePluginManager);
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
        $mockMetadataPluginManager = $this->getMock(EventBusMetadataReaderPluginManager::class);
        $mockMessagePluginManager = $this->getMock(EventBusMessagePluginManager::class);
        $expected = get_class($mockAdapter);

        $options = [
            'connectionConfig' => [

            ],
            'extraOptions' => [
                'adapter' => $expected
            ]
        ];
        $driver = new RabbitMqDriver($options, $mockMetadataPluginManager, $mockMessagePluginManager);

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
        $mockMessagePluginManager = $this->getMock(EventBusMessagePluginManager::class);

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

        $mockMetadataPluginManager = $this->getMock(EventBusMetadataReaderPluginManager::class);

        /** @var RabbitMqDriver|PHPUnit_Framework_MockObject_MockObject $mockDriver */
        $mockDriver = $this->getMock(RabbitMqDriver::class, [
            'getMetadataReader',
            'getAdapter'
        ], [
            'options' => [
                DriverConfig::CONNECTION_CONFIG => []
            ],
            'metadataReaderPluginManager' => $mockMetadataPluginManager,
            'messagePluginManager' => $mockMessagePluginManager
        ]);


        $mockDriver->expects(static::once())->method('getMetadataReader')->will(static::returnValue($metadataReaderMock));
        $mockDriver->expects(static::once())->method('getAdapter')->will(static::returnValue($mockAdapter));


        $mockDriver->initEventBus();
    }



    /**
     * Тест публикации события
     *
     */
    public function testTrigger()
    {
        $mockMessagePluginManager = $this->getMock(EventBusMessagePluginManager::class);

        $expectedEventName = 'test.event';


        /** @var PHPUnit_Framework_MockObject_MockObject|MessageInterface $messageMock */
        $messageMock = $this->getMock(MessageInterface::class);

        $metadataMock = $this->getMock(MetadataInterface::class);


        /** @var PHPUnit_Framework_MockObject_MockObject|ReaderInterface  $metadataReaderMock */
        $metadataReaderMock = $this->getMock(ReaderInterface::class, get_class_methods(ReaderInterface::class));
        $metadataReaderMock->expects(static::once())
            ->method('loadMetadataForClass')
            ->with(static::equalTo(get_class($messageMock)))
            ->will(static::returnValue($metadataMock));

        $mockAdapter = $this->getMock(AdapterInterface::class, get_class_methods(AdapterInterface::class));
        $mockAdapter->expects(static::once())
                     ->method('trigger')
                     ->with(static::equalTo($expectedEventName), static::equalTo($messageMock), static::equalTo($metadataMock));


        $mockMetadataPluginManager = $this->getMock(EventBusMetadataReaderPluginManager::class);

        /** @var RabbitMqDriver|PHPUnit_Framework_MockObject_MockObject $mockDriver */
        $mockDriver = $this->getMock(RabbitMqDriver::class, [
            'getMetadataReader',
            'getAdapter'
        ], [
            'options' => [
                DriverConfig::CONNECTION_CONFIG => []
            ],
            'metadataReaderPluginManager' => $mockMetadataPluginManager,
            'messagePluginManager' => $mockMessagePluginManager
        ]);


        $mockDriver->expects(static::once())->method('getMetadataReader')->will(static::returnValue($metadataReaderMock));
        $mockDriver->expects(static::once())->method('getAdapter')->will(static::returnValue($mockAdapter));


        $mockDriver->trigger($expectedEventName, $messageMock);
    }




    /**
     * Проверка получения имени Serializer
     *
     */
    public function testExtractSerializerName()
    {
        $rawData = [
            'test_raw_data_1'
        ];
        $expectedSerializerName = 'test_serializer_name';


        /** @var AdapterInterface|PHPUnit_Framework_MockObject_MockObject  $mockAdapter */
        $mockAdapter = $this->getMock(AdapterInterface::class, get_class_methods(AdapterInterface::class));


        $mockMetadataPluginManager = $this->getMock(EventBusMetadataReaderPluginManager::class);
        $mockMessagePluginManager = $this->getMock(EventBusMessagePluginManager::class);


        $options = [
            'connectionConfig' => [

            ],
            'extraOptions' => [
                'adapter' => get_class($mockAdapter)
            ]
        ];
        $driver = new RabbitMqDriver($options, $mockMetadataPluginManager, $mockMessagePluginManager);
        /** @var PHPUnit_Framework_MockObject_MockObject|AdapterInterface $adapter */
        $adapter = $driver->getAdapter();
        $adapter->expects(static::once())
            ->method('extractSerializerName')
            ->with(static::equalTo($rawData))
            ->will(static::returnValue($expectedSerializerName));


        $actual = $driver->extractSerializerName($rawData);

        static::assertEquals($expectedSerializerName, $actual);
    }

    /**
     * Проверка получения имени Serializer
     *
     */
    public function testExtractSerializedData()
    {
        $rawData = [
            'test_raw_data_1'
        ];
        $expectedSerializedData = 'test_serializer_data';


        /** @var AdapterInterface|PHPUnit_Framework_MockObject_MockObject  $mockAdapter */
        $mockAdapter = $this->getMock(AdapterInterface::class, get_class_methods(AdapterInterface::class));


        $mockMetadataPluginManager = $this->getMock(EventBusMetadataReaderPluginManager::class);
        $mockMessagePluginManager = $this->getMock(EventBusMessagePluginManager::class);


        $options = [
            'connectionConfig' => [

            ],
            'extraOptions' => [
                'adapter' => get_class($mockAdapter)
            ]
        ];
        $driver = new RabbitMqDriver($options, $mockMetadataPluginManager, $mockMessagePluginManager);
        /** @var PHPUnit_Framework_MockObject_MockObject|AdapterInterface $adapter */
        $adapter = $driver->getAdapter();
        $adapter->expects(static::once())
            ->method('extractSerializedData')
            ->with(static::equalTo($rawData))
            ->will(static::returnValue($expectedSerializedData));


        $actual = $driver->extractSerializedData($rawData);

        static::assertEquals($expectedSerializedData, $actual);
    }



    /**
     * Проверка получения имени Serializer
     *
     */
    public function testAttach()
    {
        $expectedMessageName = 'test_message_name';
        $expectedHandler = function () {};

        /** @var AdapterInterface|PHPUnit_Framework_MockObject_MockObject  $mockAdapter */
        $mockAdapter = $this->getMock(AdapterInterface::class, get_class_methods(AdapterInterface::class));


        $mockMetadata = $this->getMock(MetadataInterface::class);

        /** @var ReaderInterface|PHPUnit_Framework_MockObject_MockObject $metadataReader */
        $metadataReader = $this->getMock(ReaderInterface::class);
        $metadataReader->expects(static::once())
            ->method('loadMetadataForClass')
            ->with(static::equalTo($expectedMessageName))
            ->will(static::returnValue($mockMetadata));

        /** @var EventBusMetadataReaderPluginManager|PHPUnit_Framework_MockObject_MockObject $mockMetadataPluginManager */
        $mockMetadataPluginManager = $this->getMock(EventBusMetadataReaderPluginManager::class);
        $mockMetadataPluginManager->expects(static::once())
                                  ->method('get')
                                  ->will(static::returnValue($metadataReader));


        $mockMessagePluginManager = $this->getMock(EventBusMessagePluginManager::class);


        $options = [
            'connectionConfig' => [

            ],
            'extraOptions' => [
                'adapter' => get_class($mockAdapter)
            ]
        ];

        $driver = new RabbitMqDriver($options, $mockMetadataPluginManager, $mockMessagePluginManager);

        /** @var PHPUnit_Framework_MockObject_MockObject|AdapterInterface $adapter */
        $adapter = $driver->getAdapter();
        $adapter->expects(static::once())
            ->method('attach')
            ->with(static::equalTo($mockMetadata), static::isInstanceOf(MessageHandler::class));

        $driver->attach($expectedMessageName, $expectedHandler);
    }
}
