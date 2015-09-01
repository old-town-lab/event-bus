<?php
/**
 * @link https://github.com/old-town/event-buss
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBuss\Driver;

/**
 * Class DriverConfig
 *
 * @package OldTown\EventBuss\Driver
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
     * @param array $config
     *
     * @throws \OldTown\EventBuss\Driver\Exception\InvalidEventBussDriverConfigException
     * @throws \OldTown\EventBuss\Driver\Exception\InvalidArgumentException
     */
    public function __construct(array $config = [])
    {
        $this->init($config);
    }

    /**
     * @param array $config
     *
     * @throws \OldTown\EventBuss\Driver\Exception\InvalidEventBussDriverConfigException
     * @throws \OldTown\EventBuss\Driver\Exception\InvalidArgumentException
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

        $this->setExtraOptions($config);
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
     * @throws \OldTown\EventBuss\Driver\Exception\InvalidArgumentException
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
            static::EXTRA_OPTIONS => $this->getExtraOptions()
        ];

        return $config;
    }
}
