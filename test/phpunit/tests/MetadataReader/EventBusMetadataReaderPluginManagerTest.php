<?php
/**
 * @link https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\PhpUnit\Test\MetadataReader;

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
        $this->setApplicationConfig(
            include __DIR__ . '/../../_files/application.config.php'
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
        $this->setApplicationConfig(
            include __DIR__ . '/../../_files/application.config.php'
        );
        /** @var EventBusMetadataReaderPluginManager $manager */
        $manager = $this->getApplicationServiceLocator()->get(EventBusMetadataReaderPluginManager::class);

        $manager->setService('invalidService', new \stdClass());

        $manager->get('invalidService');
    }
}
