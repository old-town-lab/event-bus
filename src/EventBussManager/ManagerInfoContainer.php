<?php
/**
 * @link https://github.com/old-town/event-buss
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\EventBussManager;

/**
 * Class EventBussManagerFacade
 *
 * @package OldTown\EventBus\EventBussManagerFacade
 */
class ManagerInfoContainer
{
    /**
     * @var string
     */
    const PLUGIN_NAME = 'pluginName';

    /**
     * @var string
     */
    const DRIVER = 'driver';

    /**
     * Имя плагина зарегестрированного в EventBussPluginManager
     *
     * @var string
     */
    protected $pluginName = EventBussManagerFacade::class;

    /**
     * Имя плагина зарегестрированного в EventBussDriverManager
     *
     * @var string
     */
    protected $driver;

    /**
     * @param array $config
     *
     * @throws \OldTown\EventBus\EventBussManager\Exception\InvalidEventBussManagerConfigException
     */
    public function __construct(array $config = [])
    {
        $this->init($config);
    }

    /**
     * @param array $config
     *
     * @throws \OldTown\EventBus\EventBussManager\Exception\InvalidEventBussManagerConfigException
     */
    protected function init(array $config = [])
    {
        if (array_key_exists(static::PLUGIN_NAME, $config)) {
            $this->setPluginName($config[static::PLUGIN_NAME]);
        }
        if (!array_key_exists(static::DRIVER, $config)) {
            $errMsg = sprintf('Отсутствует секция %s', static::DRIVER);
            throw new Exception\InvalidEventBussManagerConfigException($errMsg);
        }
        $this->setDriver($config[static::DRIVER]);
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
     * @return string
     */
    public function getDriver()
    {
        return $this->driver;
    }

    /**
     * @param string $driver
     *
     * @return $this
     */
    public function setDriver($driver)
    {
        $this->driver = (string)$driver;

        return $this;
    }


    /**
     * @return array
     */
    public function getPluginConfig()
    {
        $config = [
            static::DRIVER => $this->getDriver()
        ];

        return $config;
    }
}
