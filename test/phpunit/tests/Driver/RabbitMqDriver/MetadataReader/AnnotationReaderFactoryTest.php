<?php
/**
 * @link    https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\PhpUnit\Test\Driver\RabbitMqDriver\MetadataReader;

use OldTown\EventBus\Driver\DriverConfig;
use OldTown\EventBus\Driver\RabbitMqDriver\MetadataReader\AnnotationReader;
use OldTown\EventBus\PhpUnit\TestData\TestPaths;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use OldTown\EventBus\Driver\RabbitMqDriver\MetadataReader\AnnotationReaderFactory;


/**
 * Class AnnotationReader
 *
 * @package OldTown\EventBus\Driver\RabbitMqDriver\MetadataReader
 */
class AnnotationReaderFactoryTest  extends AbstractHttpControllerTestCase
{
    /**
 * Фабрика для создания AnnotationReader
 *
 */
    public function testCreateService()
    {
        /** @noinspection PhpIncludeInspection */
        $this->setApplicationConfig(
            include TestPaths::getApplicationConfig()
        );
        $appServiceLocator = $this->getApplicationServiceLocator();

        $factory = new AnnotationReaderFactory();

        $factory->setCreationOptions([
            DriverConfig::PATHS => []
        ]);

        $reader = $factory->createService($appServiceLocator);

        static::assertInstanceOf(AnnotationReader::class, $reader);
    }

    /**
     * Фабрика для создания AnnotationReader. Проверка ситуации когда не указана секция конфига paths
     *
     * @expectedExceptionMessage Некорректная секция в конфиге: paths
     * @expectedException \OldTown\EventBus\Driver\RabbitMqDriver\MetadataReader\Exception\InvalidPathException
     */
    public function testCreateServicePathInvalid()
    {
        /** @noinspection PhpIncludeInspection */
        $this->setApplicationConfig(
            include TestPaths::getApplicationConfig()
        );
        $appServiceLocator = $this->getApplicationServiceLocator();

        $factory = new AnnotationReaderFactory();
        $factory->createService($appServiceLocator);
    }
}
