<?php
/**
 * @link https://github.com/old-town/event-buss
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBuss\Driver;

/**
 * Class PathsTrait
 *
 * @package OldTown\EventBuss\Driver
 */
trait PathsTrait
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
}
