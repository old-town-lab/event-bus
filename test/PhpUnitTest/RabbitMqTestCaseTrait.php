<?php
/**
 * @link https://github.com/old-town/event-buss
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBuss\PhpUnitTest;

use RabbitMQ\Management\APIClient;
use RabbitMQ\Management\Entity\Exchange;
use RabbitMQ\Management\Entity\Queue;

/**
 * Class ModuleTest
 *
 * @package OldTown\EventBuss\PhpUnitTest
 */
trait  RabbitMqTestCaseTrait
{
    /**
     * @var array|null
     */
    protected static $connectionRabbitMq;

    /**
     * @var APIClient
     */
    protected static $rabbitMqClient;

    /**
     * @var string
     */
    protected static $rabbitMqVirtualHost = '/';

    /**
     * Очереди которые не удалюятся при очистки
     *
     * @var array
     */
    protected static $notDeleteExchange = [
        'amq.rabbitmq.trace' => 'amq.rabbitmq.trace',
        '' => ''
    ];

    /**
     * Реализация функционала который должен вызываться перед запуском первого теста
     *
     * @param $connection
     *
     * @throws \InvalidArgumentException
     */
    protected static function setUpBeforeClassRabbitMqTestCase(array $connection = [])
    {
        static::initAPIClient($connection);
        static::setConnection($connection);
        static::clearRabbitMqVirtualHost(static::getRabbitMqVirtualHost());
    }

    /**
     * Инициализация клиента для работы с кролем
     *
     * @param array $connection
     */
    protected static function initAPIClient(array $connection = [])
    {
        $connectionApi = [];
        if (!array_key_exists('host', $connection)) {
            $errMsg = 'Отсутствует параметр host';
            throw new \InvalidArgumentException($errMsg);
        }
        $connectionApi['host'] = $connection['host'];

        if (array_key_exists('port', $connection)) {
            $connectionApi['port'] = $connection['port'];
        }
        if (array_key_exists('login', $connection)) {
            $connectionApi['username'] = $connection['login'];
        }
        if (array_key_exists('password', $connection)) {
            $connectionApi['password'] = $connection['password'];
        }
        if (array_key_exists('vhost', $connection)) {
            static::$rabbitMqVirtualHost = $connection['vhost'];
        }

        static::$rabbitMqClient = APIClient::factory($connectionApi);
    }

    /**
     * Выводит список очередей для заданого виртуалхоста
     *
     * @param $virtualHost
     * @return Queue[]
     */
    protected static function  getListQueues($virtualHost)
    {
        $list = [];
        /** @var Queue[] $listQueue */
        $listQueue = static::getRabbitMqClient()->listQueues($virtualHost);
        foreach ($listQueue as $queue) {
            $list[$queue->name] = $queue;
        }

        return $list;
    }

    /**
     * Выводит список всех обменников
     *
     * @param $virtualHost
     * @return Exchange[]
     */
    protected static function getListExchanges($virtualHost)
    {
        $list = [];
        /** @var Exchange[] $listExchange */
        $listExchange = static::getRabbitMqClient()->listExchanges($virtualHost);
        foreach ($listExchange as $exchange) {
            $list[$exchange->name] = $exchange;
        }

        return $list;
    }

    /**
     * Очистка виртуального хоста
     *
     * @param $virtualHost
     */
    protected static function clearRabbitMqVirtualHost($virtualHost)
    {
        $listQueue = static::getListQueues($virtualHost);

        foreach ($listQueue as $queue) {
            if ($queue->name) {
                static::getRabbitMqClient()->deleteQueue($virtualHost, $queue->name);
            }
        }

        $listExchange = static::getListExchanges($virtualHost);

        foreach ($listExchange as $exchange) {
            if (!array_key_exists($exchange->name, static::$notDeleteExchange)) {
                static::getRabbitMqClient()->deleteExchange($virtualHost, $exchange->name);
            }
        }
    }

    /**
     * Возвращает клиент для работы с кроликом
     *
     * @return APIClient
     */
    public static function getRabbitMqClient()
    {
        return self::$rabbitMqClient;
    }

    /**
     * Имя виртуального хоста используемого при тестирование
     *
     * @return string
     */
    public static function getRabbitMqVirtualHost()
    {
        return self::$rabbitMqVirtualHost;
    }

    /**
     * Устанавливает имя виртуального хоста используемого при тестирование
     *
     * @param string $rabbitMqVirtualHost
     */
    public static function setRabbitMqVirtualHost($rabbitMqVirtualHost)
    {
        self::$rabbitMqVirtualHost = (string)$rabbitMqVirtualHost;
    }

    /**
     * Возвращает настройки коннекта кролика
     *
     * @return array|null
     */
    public static function  getConnectionRabbitMq()
    {
        return static::$connectionRabbitMq;
    }

    /**
     * Устанавливает настройки коннекта кролика
     *
     * @param array|null $connection
     *
     * @return $this
     */
    public static function setConnection(array $connection = [])
    {
        static::$connectionRabbitMq = $connection;
    }


}
