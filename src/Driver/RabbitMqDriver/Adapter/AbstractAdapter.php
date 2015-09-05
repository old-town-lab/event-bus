<?php
/**
 * @link https://github.com/old-town/event-buss
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBuss\Driver\RabbitMqDriver\Adapter;

/**
 * Class AbstractAdapter
 *
 * @package OldTown\EventBuss\Driver\RabbitMqDriver\Adapter
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
        $this->connectionConfig = $connection;
    }

    /**
     * @return array
     */
    public function getConnectionConfig()
    {
        return $this->connectionConfig;
    }
}
