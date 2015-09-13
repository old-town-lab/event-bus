<?php
/**
 * @link https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\Driver\RabbitMqDriver\Adapter;

use AMQPConnection;
use AMQPChannel;
use AMQPExchange;
use AMQPQueue;
use OldTown\EventBus\Driver\RabbitMqDriver\MetadataReader\MetadataInterface;
use OldTown\EventBus\Message\MessageInterface;
use \OldTown\EventBus\Driver\RabbitMqDriver\Adapter\AmqpPhpExtension\RawArgument;

/**
 * Class AmqpPhpExtension
 *
 * @package OldTown\EventBus\Driver\RabbitMqDriver\Adapter
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
    protected $channelInitBus;

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
    public function getChannelInitBus()
    {
        if ($this->channelInitBus) {
            return $this->channelInitBus;
        }

        $connection = $this->getConnection();
        if (!$connection->isConnected()) {
            $connection->connect();
        }
        $this->channelInitBus = new AMQPChannel($connection);

        return $this->channelInitBus;
    }

    /**
     * Инициализация шины
     *
     * @param MetadataInterface[] $metadata
     *
     * @throws Exception\InvalidMetadataException
     * @throws \Exception
     * @throws \AMQPChannelException
     * @throws \AMQPConnectionException
     */
    public function initEventBus(array $metadata = [])
    {
        $channel = $this->getChannelInitBus();
        try {
            $channel->startTransaction();
            foreach ($metadata as $data) {
                if (!$data instanceof MetadataInterface) {
                    $errMsg = sprintf('Метаданные должны реализовывать класс %s', MetadataInterface::class);
                    throw new Exception\InvalidMetadataException($errMsg);
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
            //@fixme Не работает корректно откат
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
     * @throws Exception\InvalidExchangeTypeException
     */
    protected function createExchangeByMetadata(MetadataInterface $metadata, AMQPChannel $channel)
    {
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
     * @throws \AMQPQueueException
     * @throws \AMQPChannelException
     * @throws \AMQPConnectionException
     */
    protected function createQueueByMetadata(MetadataInterface $metadata, AMQPChannel $channel)
    {
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
     * @throws Exception\InvalidExchangeTypeException
     */
    protected function getExchangeTypeByCode($code)
    {
        if (!array_key_exists($code, static::$exchangeTypeToCode)) {
            $errMsg = sprintf('Некорректный тип обменника %s', $code);
            throw new Exception\InvalidExchangeTypeException($errMsg);
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
     * @throws Exception\InvalidExchangeTypeException
     *
     */
    public function trigger($eventName, MessageInterface $message, MetadataInterface $metadata)
    {
        $channel = $this->getChannel();
        $exchange = $this->createExchangeByMetadata($metadata, $channel);

        $messageData = $message->getContent();

        $arguments = [
            'headers' => [
                MessageInterface::SERIALIZER_HEADER => $message->getSerializerName()
            ]
        ];

        $exchange->publish($messageData, $eventName, AMQP_NOPARAM, $arguments);
    }

    /**
     * @param MetadataInterface $metadata
     * @param                   $callback
     *
     * @throws \AMQPExchangeException
     * @throws \AMQPChannelException
     * @throws \AMQPConnectionException
     * @throws \AMQPQueueException
     */
    public function attach(MetadataInterface $metadata, callable $callback)
    {
        $channel = $this->getChannel();

        $queue = $this->createQueueByMetadata($metadata, $channel);
        $queue->consume($callback);
    }

    /**
     * @param array $rawData
     *
     * @return string
     *
     * @throws \OldTown\EventBus\Driver\RabbitMqDriver\Adapter\AmqpPhpExtension\Exception\InvalidRawArgumentException
     * @throws \OldTown\EventBus\Driver\RabbitMqDriver\Adapter\Exception\InvalidSerializerNameException
     */
    public function extractSerializerName(array $rawData = [])
    {
        $data = RawArgument::factory($rawData);

        $serializer = $data->getRawMessage()->getHeader(MessageInterface::SERIALIZER_HEADER);

        if (!$serializer) {
            $errMsg = 'Отсутствуют данные о имени Serializer';
            throw new Exception\InvalidSerializerNameException($errMsg);
        }

        return $serializer;
    }

    /**
     * @param array $rawData
     *
     * @return string
     *
     * @throws \OldTown\EventBus\Driver\RabbitMqDriver\Adapter\AmqpPhpExtension\Exception\InvalidRawArgumentException
     */
    public function extractSerializedData(array $rawData = [])
    {
        $data = RawArgument::factory($rawData);

        $serializedData = $data->getRawMessage()->getBody();
        return $serializedData;
    }
}
