<?php
/**
 * @link https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\Driver;

use Traversable;
use Zend\Stdlib\ArrayUtils;
use OldTown\EventBus\MetadataReader\EventBusMetadataReaderPluginManager;


/**
 * Class AbstractDriver
 *
 * @package OldTown\EventBus\Driver
 */
abstract class AbstractDriver implements EventBusDriverInterface
{
    /**
     * Опции переданные при создание драйвера
     *
     * @var array
     */
    protected $driverOptions = [];

    /**
     * Настройки специфичные для конкретного драйвера
     *
     * @var array
     */
    protected $extraOptions = [];

    /**
     * @param array|Traversable                    $options
     *
     * @param EventBusMetadataReaderPluginManager $metadataReaderPluginManager
     *
     * @throws \OldTown\EventBus\Driver\Exception\InvalidArgumentException
     * @throws \Zend\Stdlib\Exception\InvalidArgumentException
     */
    public function __construct($options = null, EventBusMetadataReaderPluginManager $metadataReaderPluginManager = null)
    {
        if (null === $options) {
            $options = [];
        }

        if ($options instanceof Traversable) {
            $options = ArrayUtils::iteratorToArray($options);
        }
        if (!is_array($options)) {
            $errMsg = 'Некорректное значение опций';
            throw new Exception\InvalidArgumentException($errMsg);
        }

        $this->setOptions($options);
        $this->driverOptions = $options;

        if ($this instanceof MetadataReaderInterface) {
            $this->setMetadataReaderPluginManager($metadataReaderPluginManager);
        }
    }



    /**
     * @param array $options
     *
     * @return $this
     */
    public function setOptions(array $options = [])
    {
        return $this;
    }

    /**
     * Опции переданные при создание драйвера
     *
     * @return array
     */
    public function getDriverOptions()
    {
        return $this->driverOptions;
    }

    /**
     * @return array
     */
    public function getExtraOptions()
    {
        if ($this->extraOptions) {
            return $this->extraOptions;
        }
        $driverOption = $this->getDriverOptions();
        $extraOptions = [];
        if (array_key_exists(DriverConfig::EXTRA_OPTIONS, $driverOption) && is_array($driverOption[DriverConfig::EXTRA_OPTIONS])) {
            $extraOptions = $driverOption[DriverConfig::EXTRA_OPTIONS];
        }
        $this->extraOptions = $extraOptions;

        return $this->extraOptions;
    }

    /**
     * @param array $extraOptions
     *
     * @return $this
     */
    public function setExtraOptions(array $extraOptions = [])
    {
        $this->extraOptions = $extraOptions;

        return $this;
    }
}
