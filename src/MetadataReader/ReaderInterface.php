<?php
/**
 * @link https://github.com/old-town/event-buss
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBuss\MetadataReader;

use Doctrine\Common\Annotations\AnnotationReader as DoctrineAnnotationReader;


/**
 * Interface ReaderInterface
 *
 * @package OldTown\EventBuss\MetadataReader
 */
interface ReaderInterface
{
    /**
     * Добавляет пути для поиска
     *
     * @param array $paths
     *
     * @return $this
     */
    public function addPaths(array $paths);

    /**
     * Возвращает пути для поиска
     *
     * @return array
     */
    public function getPaths();

    /**
     * Добавляет пути в исключения
     *
     * @param array $paths
     *
     * @return $this
     */
    public function addExcludePaths(array $paths);

    /**
     * Возвращает пути с исключениями
     *
     * @return array
     */
    public function getExcludePaths();

    /**
     * Возвращает читалку анотаций
     *
     * @return DoctrineAnnotationReader
     */
    public function getReader();

    /**
     * Возвращает расширение, которое должно быть у файлов с метаданными
     *
     * @return string
     */
    public function getFileExtension();

    /**
     * Устанавливает расширение
     *
     * @param string $fileExtension
     *
     * @return $this
     */
    public function setFileExtension($fileExtension);

    /**
     * Определяет является ли класс конечным в цепочке наследования. Критерий: все метаданные уже загружены
     *

     * @param string $className
     *
     * @return boolean
     */
    public function isTransient($className);

    /**
     * Возвращает все имена классов, которые могут быта обработанны данным драйвером
     *
     * @return array
     *
     * @throws \OldTown\EventBuss\MetadataReader\Exception\InvalidPathException
     */
    public function getAllClassNames();
}
