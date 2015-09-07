<?php
/**
 * @link https://github.com/old-town/event-buss
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBuss\Driver\RabbitMqDriver\Adapter;

use OldTown\EventBuss\Driver\RabbitMqDriver\MetadataReader\Metadata;
use AMQPConnection;
use AMQPChannel;
use AMQPExchange;
use AMQPQueue;
use OldTown\EventBuss\Message\MessageInterface;
use OldTown\EventBuss\MetadataReader\MetadataInterface;

/**
 * Class AmqpPhpExtension
 *
 * @package OldTown\EventBuss\Driver\RabbitMqDriver\Adapter
 */
class AmqpPhpExtension extends AbstractAdapter
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
     * @var string
     */
    const PARAMS = 'params';

    /**
     * Соответствие кодов обозначающих тип обменника, значению используемому для создания обменника
     *
     * @var array
     */
    protected static $exchangeTypeToCode = [
        'direct' => AMQP_EX_TYPE_DIRECT,
        'fanout' => AMQP_EX_TYPE_FANOUT,
        'header' => AMQP_EX_TYPE_HEADERS,
        'topic' => AMQP_EX_TYPE_TOPIC,
    ];

    /**
     * Соеденение с сервером RabbitMq
     *
     * @var AMQPConnection
     */
    protected $connection;

    /**
     * Канал для взаимодействия с сервером RabbitMq
     *
     * @var AMQPChannel
     */
    protected $channel;


    /**
     * Канал используемый для инициации шины(используются транзакции). После того как был использован функционал
     * работы с транзакиями требуется выполнять комиты в ручную, после таких действий как публикация сообщений.
     * Что бы избежать этого используютется два канала, один для инициации шины, второй для всех других действий
     *
     * @var AMQPChannel
     */
    protected $channelInitBuss;

    /**
     * Имя расширения используемого для взаимодействия с сервером очередей
     *
     * @var string
     */
    protected static $amqpPhpExtensionName = 'amqp';

    /**
     * @param array $connection
     *
     * @throws Exception\AmqpPhpExtensionNotInstalledException
     */
    public function __construct(array $connection = [])
    {
        if (!extension_loaded(static::$amqpPhpExtensionName)) {
            $errMsg = sprintf('Для работы драйвера необходимо php расширение %s', static::$amqpPhpExtensionName);
            throw new Exception\AmqpPhpExtensionNotInstalledException($errMsg);
        }
        parent::__construct($connection);
    }

    /**
     * Получение соеденения для работы с сервером очередей
     *
     * @return AMQPConnection
     */
    public function getConnection()
    {
        if ($this->connection) {
            return $this->connection;
        }

        $connectionConfig = $this->getConnectionConfig();
        $params = array_key_exists(static::PARAMS, $connectionConfig) ? $connectionConfig[static::PARAMS] : [];

        $this->connection = new AMQPConnection($params);
        return $this->connection;
    }

    /**
     * Получение и создание канала для работы с сервером очередей. Данный канал используется для всех действий кроме
     * инициации шины.
     *
     * @return AMQPChannel
     *
     * @throws \AMQPConnectionException
     */
    public function getChannel()
    {
        if ($this->channel) {
            return $this->channel;
        }

        $connection = $this->getConnection();
        if (!$connection->isConnected()) {
            $connection->connect();
        }
        $this->channel = new AMQPChannel($connection);

        return $this->channel;
    }


    /**
     * Получение канали используемого для инициации шины очередей
     *
     * @return AMQPChannel
     * @throws \AMQPConnectionException
     */
    public function getChannelInitBuss()
    {
        if ($this->channelInitBuss) {
            return $this->channelInitBuss;
        }

        $connection = $this->getConnection();
        if (!$connection->isConnected()) {
            $connection->connect();
        }
        $this->channelInitBuss = new AMQPChannel($connection);

        return $this->channelInitBuss;
    }

    /**
     * Инициализация шины
     *
     * @param Metadata[] $metadata
     *
     * @throws Exception\RuntimeException
     * @throws \Exception
     * @throws \AMQPChannelException
     * @throws \AMQPConnectionException
     */
    public function initEventBuss(array $metadata = [])
    {
        $channel = $this->getChannelInitBuss();
        try {
            $channel->startTransaction();
            foreach ($metadata as $data) {
                if (!$data instanceof Metadata) {
                    $errMsg = sprintf('Метаданные должны реализовывать класс %s', Metadata::class);
                    throw new Exception\RuntimeException($errMsg);
                }
                $exchange = $this->createExchangeByMetadata($data, $channel);
                $queue = $this->createQueueByMetadata($data, $channel);


                $bindKeys = $data->getBindingKeys();
                foreach ($bindKeys as $bindKey) {
                    $queue->bind($exchange->getName(), $bindKey);
                }
            }

            $channel->commitTransaction();
        } catch (\Exception $e) {
            $channel->rollbackTransaction();
            throw $e;
        }
    }

    /**
     * Создает обменник на основе метаданных
     *
     * @param MetadataInterface $metadata
     * @param AMQPChannel $channel
     * @return AMQPExchange
     *
     * @throws Exception\RuntimeException
     * @throws \AMQPExchangeException
     * @throws \AMQPChannelException
     * @throws \AMQPConnectionException
     */
    protected function createExchangeByMetadata(MetadataInterface $metadata, AMQPChannel $channel)
    {
        if (!$metadata instanceof Metadata) {
            $errMsg = 'Неподдерживаемый тип метаданных';
            throw new Exception\RuntimeException($errMsg);
        }
        $exchange = new AMQPExchange($channel);
        $exchange->setName($metadata->getExchangeName());
        $type = $this->getExchangeTypeByCode($metadata->getExchangeType());
        $exchange->setType($type);

        if (true === $metadata->getFlagExchangeDurable()) {
            $exchange->setFlags(AMQP_DURABLE);
        }

        $exchange->declareExchange();

        return $exchange;
    }

    /**
     * Создает очередь на основе метаданных
     *
     * @param MetadataInterface $metadata
     * @param AMQPChannel $channel
     * @return AMQPQueue
     *
     * @throws Exception\RuntimeException
     * @throws \AMQPChannelException
     * @throws \AMQPConnectionException
     */
    protected function createQueueByMetadata(MetadataInterface $metadata, AMQPChannel $channel)
    {
        if (!$metadata instanceof Metadata) {
            $errMsg = 'Неподдерживаемый тип метаданных';
            throw new Exception\RuntimeException($errMsg);
        }
        $queue = new AMQPQueue($channel);
        $queue->setName($metadata->getQueueName());
        $queue->declareQueue();

        return $queue;
    }

    /**
     * Получает значение типа обменника
     *
     * @param string $code
     * @return string
     *
     * @throws Exception\RuntimeException
     */
    protected function getExchangeTypeByCode($code)
    {
        if (!array_key_exists($code, static::$exchangeTypeToCode)) {
            $errMsg = sprintf('Некорректный тип обменника %s', $code);
            throw new Exception\RuntimeException($errMsg);
        }

        $type = static::$exchangeTypeToCode[$code];

        return $type;
    }


    /**
     * Публикация сообещния в очередь
     *
     * @param $eventName
     * @param MessageInterface $message
     * @param MetadataInterface $metadata
     *
     * @throws \AMQPConnectionException
     *
     * @throws Exception\RuntimeException
     * @throws \AMQPExchangeException
     * @throws \AMQPChannelException
     *
     */
    public function trigger($eventName, MessageInterface $message, MetadataInterface $metadata)
    {
        $channel = $this->getChannel();
        $exchange = $this->createExchangeByMetadata($metadata, $channel);
        $exchange->declareExchange();
        $messageData = serialize($message);
        $exchange->publish($messageData, $eventName);
    }
}
