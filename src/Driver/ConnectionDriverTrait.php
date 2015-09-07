<?php
/**
 * @link https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\Driver;

/**
 * Class ConnectionDriverTrait
 *
 * @package OldTown\EventBus\Driver
 */
trait ConnectionDriverTrait
{
    /**
     * @var array
     */
    protected $connectionConfig;

    /**
     * @return array
     */
    abstract public function getDriverOptions();

    /**
     * @return array
     *
     * @throws \OldTown\EventBus\Driver\Exception\InvalidEventBussDriverConfigException
     */
    public function getConnectionConfig()
    {
        if ($this->connectionConfig) {
            return $this->connectionConfig;
        }
        $driverOption = $this->getDriverOptions();
        if (!array_key_exists(DriverConfig::CONNECTION_CONFIG, $driverOption)) {
            $errMsg = sprintf('Отсутствует секция %s', DriverConfig::CONNECTION_CONFIG);
            throw new Exception\InvalidEventBussDriverConfigException($errMsg);
        }
        $this->connectionConfig = $driverOption[DriverConfig::CONNECTION_CONFIG];

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
}
