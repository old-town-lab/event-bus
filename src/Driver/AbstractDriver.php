<?php
/**
 * @link https://github.com/old-town/event-buss
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBuss\Driver;

use Traversable;
use Zend\Stdlib\ArrayUtils;

/**
 * Class AbstractDriver
 *
 * @package OldTown\EventBuss\Driver
 */
abstract class AbstractDriver implements EventBussDriverInterface
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
     * @param array|Traversable $options
     *
     * @throws \Zend\Stdlib\Exception\InvalidArgumentException
     * @throws \OldTown\EventBuss\Driver\Exception\InvalidArgumentException
     */
    public function __construct($options = null)
    {
        if (null === $options) {
            $options = [];
        }

        if ($options instanceof Traversable) {
            $options = ArrayUtils::iteratorToArray($options);
        }
        if (!is_array($options)) {
            throw new Exception\InvalidArgumentException('Некорректное значение опций');
        }

        $this->setOptions($options);
        $this->driverOptions = $options;
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
