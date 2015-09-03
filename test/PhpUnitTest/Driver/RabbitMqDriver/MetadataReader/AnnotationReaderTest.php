<?php
/**
 * @link    https://github.com/old-town/event-buss
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBuss\PhpUnitTest\Driver\RabbitMqDriver\MetadataReader;

use OldTown\EventBuss\MetadataReader\EventBussMetadataReaderPluginManager;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use OldTown\EventBuss\Driver\RabbitMqDriver\MetadataReader\AnnotationReader;
use OldTown\EventBuss\TestData\Messages\Foo;
use OldTown\EventBuss\Driver\RabbitMqDriver\MetadataReader\Metadata;


/**
 * Class DriverChainTest
 *
 * @package OldTown\EventBuss\PhpUnitTest\Driver
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

        /** @var \OldTown\EventBuss\Driver\RabbitMqDriver\MetadataReader\Metadata $metadata */
        $metadata = $annotationReader->loadMetadataForClass(Foo::class);

        static::assertInstanceOf(Metadata::class, $metadata);
        static::assertEquals($metadata->getQueueName(), 'test_queue_name');
        static::assertEquals($metadata->getExchangeName(), 'test_exchange_name');
        static::assertEquals($metadata->getBindingKeys(), [
            'test_binding_key_1' => 'test_binding_key_1',
            'test_binding_key_2' => 'test_binding_key_2',
        ]);

        $cacheMetadata = $annotationReader->loadMetadataForClass(Foo::class);
        static::assertTrue($metadata === $cacheMetadata);
    }


    /**
     * Проверка поведения, для случая когда отсутствуют метаданные
     *
     * @expectedException \OldTown\EventBuss\Driver\RabbitMqDriver\MetadataReader\Exception\InvalidClassException
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
