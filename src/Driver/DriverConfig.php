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
     * Имя плагина зарегестрированного в EventBussDriverManager
     *
     * @var string
     */
    protected $pluginName;

    /**
     * @param array $config
     *
     * @throws \OldTown\EventBuss\Driver\Exception\InvalidEventBussDriverConfigException
     */
    public function __construct(array $config = [])
    {
        $this->init($config);
    }

    /**
     * @param array $config
     *
     * @throws \OldTown\EventBuss\Driver\Exception\InvalidEventBussDriverConfigException
     */
    protected function init(array $config = [])
    {
        if (!array_key_exists(static::PLUGIN_NAME, $config)) {
            $errMsg = sprintf('Отсутствует секция %s', static::PLUGIN_NAME);
            throw new Exception\InvalidEventBussDriverConfigException($errMsg);
        }
        $this->setPluginName($config[static::PLUGIN_NAME]);
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
    public function getPluginConfig()
    {
        $config = [

        ];

        return $config;
    }
}
