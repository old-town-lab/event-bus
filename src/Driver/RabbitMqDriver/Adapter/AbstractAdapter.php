<?php
/**
 * @link https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\Driver\RabbitMqDriver\Adapter;

/**
 * Class AbstractAdapter
 *
 * @package OldTown\EventBus\Driver\RabbitMqDriver\Adapter
 */
abstract class AbstractAdapter implements AdapterInterface
{
    /**
     * Настройки соеденения
     *
     * @var array|null
     */
    protected $connectionConfig;

    /**
     * @param $connection
     */
    public function __construct(array $connection = [])
    {
        $this->setConnectionConfig($connection);
    }

    /**
     * @return array
     */
    public function getConnectionConfig()
    {
        return $this->connectionConfig;
    }

    /**
     * @param array|null $connectionConfig
     *
     * @return $this
     */
    public function setConnectionConfig(array $connectionConfig = [])
    {
        $this->connectionConfig = $connectionConfig;

        return $this;
    }
}
