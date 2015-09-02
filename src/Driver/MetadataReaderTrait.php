<?php
/**
 * @link https://github.com/old-town/event-buss
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBuss\Driver;

use OldTown\EventBuss\MetadataReader\EventBussMetadataReaderPluginManager;
use OldTown\EventBuss\MetadataReader\ReaderInterface;

/**
 * Class PathsTrait
 *
 * @package OldTown\EventBuss\Driver
 */
trait MetadataReaderTrait
{
    /**
     * Пути до директорий в которых расположенны классы описывающие передаваемые сообщения
     *
     * @var array
     */
    protected $paths;

    /**
     * @return array
     */
    abstract public function getDriverOptions();



    /**
     * @var EventBussMetadataReaderPluginManager
     */
    protected $metadataReaderPluginManager;

    /**
     * Имя реадера метаданных
     *
     * @var string
     */
    protected $metadataReaderName;

    /**
     * Ридер метаданных
     *
     * @var ReaderInterface
     */
    protected $metadataReader;



    /**
     * Возвращает пути до директории с метаданными описвающие сообщения передаваемые через шину
     *
     * @return array
     */
    public function getPaths()
    {
        if ($this->paths) {
            return $this->paths;
        }

        $driverOptions = $this->getDriverOptions();

        $pathsToMessage = [];
        if (array_key_exists(DriverConfig::PATHS, $driverOptions) && is_array($driverOptions[DriverConfig::PATHS])) {
            $pathsToMessage = $driverOptions[DriverConfig::PATHS];
        }

        $this->paths = $pathsToMessage;
        return $this->paths;
    }

    /**
     * Устанавливает пути до директории с метаданными описвающие сообщения передаваемые через шину
     *
     * @param array $paths
     *
     * @return $this
     */
    public function setPaths(array $paths = [])
    {
        $this->paths = $paths;

        return $this;
    }

    /**
     * @return EventBussMetadataReaderPluginManager
     */
    public function getMetadataReaderPluginManager()
    {
        return $this->metadataReaderPluginManager;
    }

    /**
     * @param EventBussMetadataReaderPluginManager $metadataReaderPluginManager
     *
     * @return $this
     */
    public function setMetadataReaderPluginManager(EventBussMetadataReaderPluginManager $metadataReaderPluginManager)
    {
        $this->metadataReaderPluginManager = $metadataReaderPluginManager;

        return $this;
    }


    /**
     * Возвращает имя используемого ридера метаданных
     *
     * @return string
     *
     * @throws \OldTown\EventBuss\Driver\Exception\InvalidMetadataReaderNameException
     */
    public function getMetadataReaderName()
    {
        if ($this->metadataReaderName) {
            return $this->metadataReaderName;
        }

        $options = $this->getDriverOptions();

        $metadataReaderName = null;
        $defaultMetadataReaderNameProperty = 'defaultMetadataReaderName';
        if (array_key_exists(DriverConfig::METADATA_READER, $options) && $options[DriverConfig::METADATA_READER]) {
            $metadataReaderName = $options[DriverConfig::METADATA_READER];
        } elseif (property_exists($this, 'defaultMetadataReaderName')) {
            $metadataReaderName = $this->{$defaultMetadataReaderNameProperty};
        }
        if (!$metadataReaderName) {
            $errMsg = 'Некорректное значение опций';
            throw new Exception\InvalidMetadataReaderNameException($errMsg);
        }

        $this->metadataReaderName = $metadataReaderName;
        return $this->metadataReaderName;
    }

    /**
     *
     * @return ReaderInterface
     *
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     * @throws \Zend\ServiceManager\Exception\ServiceNotCreatedException
     * @throws \Zend\ServiceManager\Exception\RuntimeException
     * @throws \OldTown\EventBuss\Driver\Exception\InvalidMetadataReaderNameException
     */
    public function getMetadataReader()
    {
        if ($this->metadataReader) {
            return $this->metadataReader;
        }

        $paths = $this->getPaths();
        $pluginManager = $this->getMetadataReaderPluginManager();
        $name = $this->getMetadataReaderName();
        $reader = $pluginManager->get($name, $paths);

        $this->metadataReader = $reader;

        return $reader;
    }
}
