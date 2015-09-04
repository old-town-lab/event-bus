<?php
/**
 * @link https://github.com/old-town/event-buss
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBuss\PhpUnitTest;

use RabbitMQ\Management\APIClient;

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


    protected function clearRabbitMqVirtualHost($virtualHost)
    {
        $listExchange = static::getRabbitMqClient()->listExchanges($virtualHost);


        var_dump($listExchange);
    }

    /**
     * @return APIClient
     */
    public static function getRabbitMqClient()
    {
        return self::$rabbitMqClient;
    }

    /**
     * @return string
     */
    public static function getRabbitMqVirtualHost()
    {
        return self::$rabbitMqVirtualHost;
    }

    /**
     * @param string $rabbitMqVirtualHost
     */
    public static function setRabbitMqVirtualHost($rabbitMqVirtualHost)
    {
        self::$rabbitMqVirtualHost = (string)$rabbitMqVirtualHost;
    }

    /**
     * @return array|null
     */
    public static function  getConnectionRabbitMq()
    {
        return static::$connectionRabbitMq;
    }

    /**
     * @param array|null $connection
     *
     * @return $this
     */
    public static function setConnection(array $connection = [])
    {
        static::$connectionRabbitMq = $connection;
    }


}
