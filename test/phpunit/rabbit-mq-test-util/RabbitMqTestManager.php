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
use AMQPConnection;
use AMQPChannel;
use AMQPEnvelope;


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
     * Конфиг соеденения с Api кролика
     *
     * @var array
     */
    protected $connection;

    /**
     * Конфиг соеденения с кроликом
     *
     * @var array
     */
    protected $connectionForTest;

    /**
     * @var AMQPChannel
     */
    protected $testChanel;

    /**
     * @var AMQPConnection
     */
    protected $testConnection;

    /**
     * @var APIClient
     */
    protected $client;

    /**
     * Очереди которые не удалюятся при очистки.
     *
     * @var array
     */
    protected static $notDeleteExchange = [
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
     * @param array $connectionForTest
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(array $connection = [], array $connectionForTest = [], $testVirtualHost)
    {
        $this->connection = $connection;
        $this->connectionForTest = $connectionForTest;

        if (!(is_string($testVirtualHost) && strlen(trim($testVirtualHost)) > 0)) {
            $errMsg = 'Некорректный форма виртуального хоста для тестирования';
            throw new \InvalidArgumentException($errMsg);
        }
        $this->testVirtualHost = $testVirtualHost;
    }


    /**
     * Инициализация клиента для работы с кролем
     *
     * @return APIClient
     * @throws \InvalidArgumentException
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
     *
     * @throws \InvalidArgumentException
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
     *
     * @throws \InvalidArgumentException
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
     * @throws \InvalidArgumentException
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
            if (!array_key_exists($exchange->name, static::$notDeleteExchange)) {
                $this->getClient()->deleteExchange($this->getTestVirtualHost(), $exchange->name);
            }
        }
    }

    /**
     * Получает информацию о обменнике
     *
     * @param $name
     * @return Exchange
     *
     * @throws \InvalidArgumentException
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
     *
     * @throws \InvalidArgumentException
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
     *
     * @throws \InvalidArgumentException
     */
    public function getBindingsByExchangeAndQueue($exchangeName, $queueName)
    {
        $bindings = $this->getClient()->listBindingsByExchangeAndQueue($this->getTestVirtualHost(), $exchangeName, $queueName);

        return $bindings;
    }

    /**
     * @return AMQPChannel
     *
     * @throws \AMQPConnectionException
     */
    public function getTestChanel()
    {
        if ($this->testChanel) {
            return $this->testChanel;
        }

        $connection = $this->getTestConnection();
        if (!$connection->isConnected()) {
            $connection->connect();
        }
        $this->testChanel = new AMQPChannel($connection);

        return $this->testChanel;
    }

    /**
     * Получение соеденения для работы с сервером очередей
     *
     * @return AMQPConnection
     */
    public function getTestConnection()
    {
        if ($this->testConnection) {
            return $this->testConnection;
        }

        $this->testConnection = new AMQPConnection($this->connectionForTest);
        return $this->testConnection;
    }

    /**
     * Читает сообщения из очереди
     *
     * @param $queue
     * @throws \InvalidArgumentException
     * @throws \AMQPConnectionException
     * @throws \AMQPQueueException
     * @throws \AMQPChannelException
     *
     * @return AMQPEnvelope[]
     */
    public function readMessagesFromQueue($queue)
    {
        $queueInfo = $this->getQueue($queue);

        $chanel = $this->getTestChanel();

        $queue = new \AMQPQueue($chanel);
        $queue->setName($queueInfo->name);
        $queue->declareQueue();

        $messages = [];
        while ($message = $queue->get()) {
            $messages[] = $message;
        }


        return $messages;
    }
}
