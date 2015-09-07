<?php
/**
 * @link https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\PhpUnit\Test\MetadataReader;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use OldTown\EventBus\MetadataReader\EventBussMetadataReaderPluginManager;


/**
 * Class EventBussMetadataReaderPluginManagerTest
 *
 * @package OldTown\EventBus\PhpUnit\Test\MetadataReader
 */
class EventBussMetadataReaderPluginManagerTest extends AbstractHttpControllerTestCase
{
    /**
     * Проверка создания менеджера шины событий через абстрактную фабрику
     *
     */
    public function testGetEventBussMetadataReaderPluginManager()
    {
        $this->setApplicationConfig(
            include __DIR__ . '/../../_files/application.config.php'
        );
        /** @var EventBussMetadataReaderPluginManager $manager */
        $manager = $this->getApplicationServiceLocator()->get(EventBussMetadataReaderPluginManager::class);

        static::assertInstanceOf(EventBussMetadataReaderPluginManager::class, $manager);
    }

    /**
     * Проверка создания менеджера шины событий через абстрактную фабрику
     *
     * @expectedException \OldTown\EventBus\MetadataReader\Exception\InvalidEventBussMetadataReaderException
     * @expectedExceptionMessage MetadataReader должен реализовывать OldTown\EventBus\MetadataReader\ReaderInterface
     */
    public function testCreateNotValidMetadataReader()
    {
        $this->setApplicationConfig(
            include __DIR__ . '/../../_files/application.config.php'
        );
        /** @var EventBussMetadataReaderPluginManager $manager */
        $manager = $this->getApplicationServiceLocator()->get(EventBussMetadataReaderPluginManager::class);

        $manager->setService('invalidService', new \stdClass());

        $manager->get('invalidService');
    }
}
