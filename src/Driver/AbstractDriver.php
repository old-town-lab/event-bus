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
}
