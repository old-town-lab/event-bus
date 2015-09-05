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
     * @var AMQPConnection
     */
    protected $connection;

    /**
     * @var AMQPChannel
     */
    protected $chanel;

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
     * @return AMQPChannel
     */
    public function getChanel()
    {
        if ($this->chanel) {
            return $this->chanel;
        }

        $connection = $this->getConnection();
        if (!$connection->isConnected()) {
            $connection->connect();
        }
        $this->chanel = new AMQPChannel($connection);

        return $this->chanel;
    }

    /**
     * Инициализация шины
     *
     * @param Metadata[] $metadata
     *
     * @throws Exception\RuntimeException
     * @throws \Exception
     */
    public function initEventBuss(array $metadata = [])
    {
        $chanel = $this->getChanel();
        try {
            $chanel->startTransaction();
            foreach ($metadata as $data) {
                if (!$data instanceof Metadata) {
                    $errMsg = sprintf('Метаданные должны реализовывать класс %s', Metadata::class);
                    throw new Exception\RuntimeException($errMsg);
                }
                $exchange = $this->createExchangeByMetadata($data);
                $queue = $this->createQueueByMetadata($data);


                $bindKeys = $data->getBindingKeys();
                foreach ($bindKeys as $bindKey) {
                    $queue->bind($exchange->getName(), $bindKey);
                }
            }

            $chanel->commitTransaction();
        } catch (\Exception $e) {
            $chanel->rollbackTransaction();
            throw $e;
        }
    }

    /**
     * Создает обменник на основе метаданных
     *
     * @param Metadata $metadata
     * @return AMQPExchange
     */
    protected function createExchangeByMetadata(Metadata $metadata)
    {
        $chanel = $this->getChanel();
        $exchange = new AMQPExchange($chanel);
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
     * @param Metadata $metadata
     * @return AMQPQueue
     */
    protected function createQueueByMetadata(Metadata $metadata)
    {
        $chanel = $this->getChanel();
        $queue = new AMQPQueue($chanel);
        $queue->setName($metadata->getQueueName());
        $queue->declareQueue();

        return $queue;
    }

    /**
     * Получает значение типа обменника
     *
     * @param string $code
     * @return string
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
}
