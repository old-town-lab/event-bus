<?php
/**
 * @link https://github.com/old-town/event-buss
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBuss\PhpUnitTest\MetadataReader;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use OldTown\EventBuss\MetadataReader\EventBussMetadataReaderPluginManager;


/**
 * Class EventBussMetadataReaderPluginManagerTest
 *
 * @package OldTown\EventBuss\PhpUnitTest\MetadataReader
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
     * @expectedException \OldTown\EventBuss\MetadataReader\Exception\InvalidEventBussMetadataReaderException
     * @expectedExceptionMessage MetadataReader должен реализовывать OldTown\EventBuss\MetadataReader\ReaderInterface
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
