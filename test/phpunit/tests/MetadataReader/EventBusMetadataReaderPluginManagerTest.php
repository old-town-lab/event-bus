<?php
/**
 * @link https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\PhpUnit\Test\MetadataReader;

use OldTown\EventBus\PhpUnit\TestData\TestPaths;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use OldTown\EventBus\MetadataReader\EventBusMetadataReaderPluginManager;


/**
 * Class EventBusMetadataReaderPluginManagerTest
 *
 * @package OldTown\EventBus\PhpUnit\Test\MetadataReader
 */
class EventBusMetadataReaderPluginManagerTest extends AbstractHttpControllerTestCase
{
    /**
     * Проверка создания менеджера шины событий через абстрактную фабрику
     *
     */
    public function testGetEventBusMetadataReaderPluginManager()
    {
        /** @noinspection PhpIncludeInspection */
        $this->setApplicationConfig(
            include TestPaths::getApplicationConfig()
        );
        /** @var EventBusMetadataReaderPluginManager $manager */
        $manager = $this->getApplicationServiceLocator()->get(EventBusMetadataReaderPluginManager::class);

        static::assertInstanceOf(EventBusMetadataReaderPluginManager::class, $manager);
    }

    /**
     * Проверка создания менеджера шины событий через абстрактную фабрику
     *
     * @expectedException \OldTown\EventBus\MetadataReader\Exception\InvalidEventBusMetadataReaderException
     * @expectedExceptionMessage MetadataReader должен реализовывать OldTown\EventBus\MetadataReader\ReaderInterface
     */
    public function testCreateNotValidMetadataReader()
    {
        /** @noinspection PhpIncludeInspection */
        $this->setApplicationConfig(
            include TestPaths::getApplicationConfig()
        );
        /** @var EventBusMetadataReaderPluginManager $manager */
        $manager = $this->getApplicationServiceLocator()->get(EventBusMetadataReaderPluginManager::class);

        $manager->setService('invalidService', new \stdClass());

        $manager->get('invalidService');
    }
}
