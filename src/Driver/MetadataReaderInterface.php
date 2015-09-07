<?php
/**
 * @link https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\Driver;

use OldTown\EventBus\MetadataReader\EventBussMetadataReaderPluginManager;
use OldTown\EventBus\MetadataReader\ReaderInterface;

/**
 * Interface PathsInterface
 *
 * @package OldTown\EventBus\Driver
 */
interface MetadataReaderInterface
{
    /**
     * Возвращает пути до директории с метаданными описвающие сообщения передаваемые через шину
     *
     * @return array
     */
    public function getPaths();

    /**
     * Устанавливает пути до директории с метаданными описвающие сообщения передаваемые через шину
     *
     * @param array $paths
     *
     * @return $this
     */
    public function setPaths(array $paths = []);

    /**
     * @return EventBussMetadataReaderPluginManager
     */
    public function getMetadataReaderPluginManager();

    /**
     * @param EventBussMetadataReaderPluginManager $metadataReaderPluginManager
     *
     * @return $this
     */
    public function setMetadataReaderPluginManager(EventBussMetadataReaderPluginManager $metadataReaderPluginManager);


    /**
     * Возвращает имя используемого ридера метаданных
     *
     * @return string
     *
     * @throws \OldTown\EventBus\Driver\Exception\InvalidMetadataReaderNameException
     */
    public function getMetadataReaderName();

    /**
     *
     * @return ReaderInterface
     *
     * @throws \OldTown\EventBus\Driver\Exception\InvalidMetadataReaderNameException
     */
    public function getMetadataReader();
}
