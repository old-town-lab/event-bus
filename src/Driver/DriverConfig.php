<?php
/**
 * @link https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\Driver;

/**
 * Class DriverConfig
 *
 * @package OldTown\EventBus\Driver
 */
class DriverConfig
{
    /**
     * @var string
     */
    const PLUGIN_NAME = 'pluginName';
    /**
     *
     * @var string
     */
    const DRIVERS = 'drivers';
    /**
     *
     * @var string
     */
    const CONNECTION = 'connection';
    /**
     *
     * @var string
     */
    const CONNECTION_CONFIG = 'connectionConfig';
    /**
     *
     * @var string
     */
    const EXTRA_OPTIONS = 'extraOptions';
    /**
     *
     * @var string
     */
    const PATHS = 'paths';
    /**
     *
     * @var string
     */
    const METADATA_READER = 'metadataReader';

    /**
     * Имя плагина зарегестрированного в EventBussDriverManager
     *
     * @var string
     */
    protected $pluginName;

    /**
     * Секция описывает используемые драйвера. Используется только в DriverChain
     *
     * @var []
     */
    protected $drivers = [];

    /**
     * Имя соедения используемое для драйвера (в конфиге приложения event_buss|connection)
     *
     * @var string|null
     */
    protected $connection;

    /**
     * Настройки соеденения
     *
     * @var array
     */
    protected $connectionConfig = [];

    /**
     * Настройки специфичные для конкретного драйвера
     *
     * @var array
     */
    protected $extraOptions = [];

    /**
     * Путь до директории в которой расположенны классы описывающие сообещния передаваемые по шине
     *
     * @var array
     */
    protected $paths = [];

    /**
     * Имя ридера метаданных
     *
     * @var string
     */
    protected $metadataReader;

    /**
     * @param array $config
     *
     * @throws \OldTown\EventBus\Driver\Exception\InvalidEventBussDriverConfigException
     * @throws \OldTown\EventBus\Driver\Exception\InvalidArgumentException
     */
    public function __construct(array $config = [])
    {
        $this->init($config);
    }

    /**
     * @param array $config
     *
     * @throws \OldTown\EventBus\Driver\Exception\InvalidEventBussDriverConfigException
     * @throws \OldTown\EventBus\Driver\Exception\InvalidArgumentException
     */
    protected function init(array $config = [])
    {
        if (!array_key_exists(static::PLUGIN_NAME, $config)) {
            $errMsg = sprintf('Отсутствует секция %s', static::PLUGIN_NAME);
            throw new Exception\InvalidEventBussDriverConfigException($errMsg);
        }
        $this->setPluginName($config[static::PLUGIN_NAME]);
        unset($config[static::PLUGIN_NAME]);

        if (array_key_exists(static::DRIVERS, $config) && is_array($config[static::DRIVERS])) {
            $this->setDrivers($config[static::DRIVERS]);
            unset($config[static::DRIVERS]);
        }
        if (array_key_exists(static::CONNECTION, $config)) {
            $this->setConnection($config[static::CONNECTION]);
            unset($config[static::CONNECTION]);
        }
        if (array_key_exists(static::CONNECTION_CONFIG, $config) && is_array($config[static::CONNECTION_CONFIG])) {
            $this->setConnectionConfig($config[static::CONNECTION_CONFIG]);
            unset($config[static::CONNECTION_CONFIG]);
        }
        if (array_key_exists(static::PATHS, $config) && is_array($config[static::PATHS])) {
            $this->setPaths($config[static::PATHS]);
            unset($config[static::PATHS]);
        }
        if (array_key_exists(static::METADATA_READER, $config) && is_string($config[static::METADATA_READER])) {
            $this->setMetadataReader($config[static::METADATA_READER]);
            unset($config[static::METADATA_READER]);
        }

        $this->setExtraOptions($config);
    }

    /**
     * @return string
     */
    public function getMetadataReader()
    {
        return $this->metadataReader;
    }

    /**
     * @param string $metadataReader
     *
     * @return $this
     */
    public function setMetadataReader($metadataReader)
    {
        $this->metadataReader = (string)$metadataReader;

        return $this;
    }



    /**
     * @return array
     */
    public function getPaths()
    {
        return $this->paths;
    }

    /**
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
     * @return array
     */
    public function getExtraOptions()
    {
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


    /**
     * @return array
     */
    public function getConnectionConfig()
    {
        return $this->connectionConfig;
    }

    /**
     * @param array $connectionConfig
     *
     * @return $this
     */
    public function setConnectionConfig(array $connectionConfig = [])
    {
        $this->connectionConfig = $connectionConfig;

        return $this;
    }


    /**
     * @return string
     */
    public function getPluginName()
    {
        return $this->pluginName;
    }

    /**
     * @param string $pluginName
     *
     * @return $this
     */
    public function setPluginName($pluginName)
    {
        $this->pluginName = (string)$pluginName;

        return $this;
    }

    /**
     * @return array
     */
    public function getDrivers()
    {
        return $this->drivers;
    }

    /**
     * @param array $drivers
     *
     * @return $this
     */
    public function setDrivers(array $drivers = [])
    {
        $this->drivers = $drivers;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * @param null|string $connection
     *
     * @return $this
     * @throws \OldTown\EventBus\Driver\Exception\InvalidArgumentException
     */
    public function setConnection($connection)
    {
        try {
            $flag = settype($connection, 'string');
        } catch (\Exception $e) {
            $flag = false;
        }
        if (!$flag) {
            $errMsg = 'Имя соеденения должно быть строкой';
            throw new Exception\InvalidArgumentException($errMsg);
        }
        $this->connection = $connection;

        return $this;
    }


    /**
     * Конфиг  с найстройкой драйвера шины событий
     *
     * @return array
     */
    public function getPluginConfig()
    {
        $config = [
            static::DRIVERS => $this->getDrivers(),
            static::CONNECTION => $this->getConnection(),
            static::CONNECTION_CONFIG => $this->getConnectionConfig(),
            static::EXTRA_OPTIONS => $this->getExtraOptions(),
            static::METADATA_READER => $this->getMetadataReader(),
            static::PATHS => $this->getPaths()
        ];

        return $config;
    }
}
