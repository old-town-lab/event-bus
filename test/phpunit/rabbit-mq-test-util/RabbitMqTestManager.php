<?php
/**
 * @link https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\PhpUnit\RabbitMqTestUtils;

use RabbitMQ\Management\APIClient;
use RabbitMQ\Management\Entity\Exchange;
use RabbitMQ\Management\Entity\Queue;
use RabbitMQ\Management\Entity\Binding;


/**
 * Class RabbitMqTestManager
 * @package OldTown\EventBus\PhpUnit\RabbitMqTestUtils
 */
class  RabbitMqTestManager
{
    /**
     * @var string
     */
    const HOST = 'host';

    /**
     * @var string
     */
    const PORT = 'port';

    /**
     * @var string
     */
    const PORT_API = 'portApi';

    /**
     * @var string
     */
    const LOGIN = 'login';

    /**
     * @var string
     */
    const PASSWORD = 'password';

    /**
     * @var string
     */
    const VHOST = 'vhost';

    /**
     * Имя хоста используемого для тестирования
     *
     * @var string
     */
    protected $testVirtualHost;

    /**
     * Конфиг соеденения с кроликом
     *
     * @var array
     */
    protected $connection;

    /**
     * Очереди которые не удалюятся при очистки.
     *
     * @var array
     */
    protected $notDeleteExchange = [
        'amq.rabbitmq.trace' => 'amq.rabbitmq.trace',
        '' => '',
        'amq.rabbitmq.log' => 'amq.rabbitmq.log'
    ];

    /**
     * Возвращает имя виртуального хоста кролика, используемого для тестирования
     *
     * @return string
     */
    public function getTestVirtualHost()
    {
        return $this->testVirtualHost;
    }

    /**
     * @return array
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * @param array $connection
     * @param $testVirtualHost
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(array $connection = [], $testVirtualHost)
    {
        $this->connection = $connection;
        if (!(is_string($testVirtualHost) && strlen(trim($testVirtualHost)) > 0)) {
            $errMsg = 'Некорректный форма виртуального хоста для тестирования';
            throw new \InvalidArgumentException($errMsg);
        }
        $this->testVirtualHost = $testVirtualHost;
    }
    /**
     * @var APIClient
     */
    protected $client;

    /**
     * Инициализация клиента для работы с кролем
     *
     * @return APIClient
     */
    protected function getClient()
    {
        if ($this->client) {
            return $this->client;
        }
        $connection = $this->getConnection();

        $connectionApi = [];
        if (!array_key_exists(static::HOST, $connection)) {
            $errMsg = 'Отсутствует параметр host';
            throw new \InvalidArgumentException($errMsg);
        }
        $connectionApi['host'] = $connection[static::HOST];

        if (array_key_exists(static::PORT_API, $connection)) {
            $connectionApi['port'] = $connection[static::PORT_API];
        }
        if (array_key_exists(static::LOGIN, $connection)) {
            $connectionApi['username'] = $connection[static::LOGIN];
        }
        if (array_key_exists(static::PASSWORD, $connection)) {
            $connectionApi['password'] = $connection[static::PASSWORD];
        }

        $this->client = APIClient::factory($connectionApi);

        return $this->client;
    }

    /**
     * Выводит список очередей для заданого виртуалхоста
     *
     * @return Queue[]
     */
    public function getListQueues()
    {
        $list = [];
        /** @var Queue[] $listQueue */
        $listQueue = $this->getClient()->listQueues($this->getTestVirtualHost());
        foreach ($listQueue as $queue) {
            $list[$queue->name] = $queue;
        }

        return $list;
    }

    /**
     * Выводит список всех обменников
     *
     * @return Exchange[]
     */
    public function getListExchanges()
    {
        $list = [];
        /** @var Exchange[] $listExchange */
        $listExchange = $this->getClient()->listExchanges($this->getTestVirtualHost());
        foreach ($listExchange as $exchange) {
            $list[$exchange->name] = $exchange;
        }

        return $list;
    }

    /**
     * Очистка виртуального хоста
     *
     */
    public function clearRabbitMqVirtualHost()
    {
        $listQueue = $this->getListQueues();

        foreach ($listQueue as $queue) {
            if ($queue->name) {
                $this->getClient()->deleteQueue($this->getTestVirtualHost(), $queue->name);
            }
        }

        $listExchange = $this->getListExchanges();

        foreach ($listExchange as $exchange) {
            if (!array_key_exists($exchange->name, $this->notDeleteExchange)) {
                $this->getClient()->deleteExchange($this->getTestVirtualHost(), $exchange->name);
            }
        }
    }

    /**
     * Получает информацию о обменнике
     *
     * @param $name
     * @return Exchange
     */
    public function getExchange($name)
    {
        $exchange = $this->getClient()->getExchange($this->getTestVirtualHost(), $name);

        return $exchange;
    }

    /**
     * Получает информацию о очереди
     *
     * @param $name
     * @return Queue
     */
    public function getQueue($name)
    {
        $queue = $this->getClient()->getQueue($this->getTestVirtualHost(), $name);

        return $queue;
    }

    /**
     * Получаем информацию о связях обменника и очереди
     *
     * @param $exchangeName
     * @param $queueName
     * @return Binding[]
     */
    public function getBindingsByExchangeAndQueue($exchangeName, $queueName)
    {
        $bindings = $this->getClient()->listBindingsByExchangeAndQueue($this->getTestVirtualHost(), $exchangeName, $queueName);

        return $bindings;
    }
}
