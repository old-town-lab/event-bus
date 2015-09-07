<?php
/**
 * @link    https://github.com/old-town/event-buss
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\PhpUnit\Test\Driver\RabbitMqDriver\MetadataReader;

use OldTown\EventBus\MetadataReader\EventBussMetadataReaderPluginManager;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use OldTown\EventBus\Driver\RabbitMqDriver\MetadataReader\AnnotationReader;
use \OldTown\EventBus\PhpUnit\TestData\Messages\Foo;
use OldTown\EventBus\Driver\RabbitMqDriver\MetadataReader\Metadata;


/**
 * Class DriverChainTest
 *
 * @package OldTown\EventBus\PhpUnit\Test\Driver
 */
class AnnotationReaderTest extends AbstractHttpControllerTestCase
{
    /**
     * Загрузка метаданных на основе класса
     *
     */
    public function testLoadMetadataForClass()
    {
        $this->setApplicationConfig(
            include __DIR__ . '/../../../../_files/application.config.php'
        );
        /** @var EventBussMetadataReaderPluginManager $metadataReaderPluginManager */
        $metadataReaderPluginManager = $this->getApplicationServiceLocator()->get(EventBussMetadataReaderPluginManager::class);

        /** @var AnnotationReader $annotationReader */
        $annotationReader = $metadataReaderPluginManager->get(AnnotationReader::class);

        /** @var \OldTown\EventBus\Driver\RabbitMqDriver\MetadataReader\Metadata $metadata */
        $metadata = $annotationReader->loadMetadataForClass(Foo::class);

        static::assertInstanceOf(Metadata::class, $metadata);
        static::assertEquals($metadata->getQueueName(), 'test_queue_name_foo');
        static::assertEquals($metadata->getExchangeName(), 'test_exchange_name_foo');
        static::assertEquals($metadata->getExchangeType(), 'topic');
        static::assertEquals($metadata->getBindingKeys(), [
            '*.procedure.*' => '*.procedure.*',
            'create.procedure.*' => 'create.procedure.*',
        ]);

        $cacheMetadata = $annotationReader->loadMetadataForClass(Foo::class);
        static::assertTrue($metadata === $cacheMetadata);
    }


    /**
     * Проверка поведения, для случая когда отсутствуют метаданные
     *
     * @expectedException \OldTown\EventBus\Driver\RabbitMqDriver\MetadataReader\Exception\InvalidClassException
     * @expectedExceptionMessage Класс не содержит необходимых метаданных : stdClass
     */
    public function testLoadMetadataForInvalidClass()
    {
        $this->setApplicationConfig(
            include __DIR__ . '/../../../../_files/application.config.php'
        );
        /** @var EventBussMetadataReaderPluginManager $metadataReaderPluginManager */
        $metadataReaderPluginManager = $this->getApplicationServiceLocator()->get(EventBussMetadataReaderPluginManager::class);

        /** @var AnnotationReader $annotationReader */
        $annotationReader = $metadataReaderPluginManager->get(AnnotationReader::class);

        $annotationReader->loadMetadataForClass(\stdClass::class);
    }
}
