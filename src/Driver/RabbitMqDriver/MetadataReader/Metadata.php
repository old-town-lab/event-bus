<?php
/**
 * @link https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\Driver\RabbitMqDriver\MetadataReader;

/**
 * Class Metadata
 *
 * @package OldTown\EventBus\Driver\RabbitMqDriver\MetadataReader
 */
class Metadata implements MetadataInterface
{
    /**
     * Имя очереди
     *
     * @var string
     */
    protected $queueName;

    /**
     * Имя обменника
     *
     * @var string
     */
    protected $exchangeName;

    /**
     * Ключи связвающие очередь и обменник
     *
     * @var array
     */
    protected $bindingKeys = [];

    /**
     * Тип обменника
     *
     * @var string
     */
    protected $exchangeType;

    /**
     * @var boolean|null
     */
    protected $flagExchangeDurable;

    /**
     * @param Annotations\EventBusMessage $metadata
     */
    public function __construct(Annotations\EventBusMessage $metadata)
    {
        $this->init($metadata);
    }

    /**
     * @return bool|null
     */
    public function getFlagExchangeDurable()
    {
        return $this->flagExchangeDurable;
    }

    /**
     * @param bool|null $flagExchangeDurable
     * @return $this
     */
    public function setFlagExchangeDurable($flagExchangeDurable = null)
    {
        $this->flagExchangeDurable = null === $flagExchangeDurable ? null : (boolean)$flagExchangeDurable;

        return $this;
    }


    /**
     * @return string
     */
    public function getExchangeType()
    {
        return $this->exchangeType;
    }

    /**
     * @param string $exchangeType
     * @return $this
     */
    public function setExchangeType($exchangeType)
    {
        $this->exchangeType = (string)$exchangeType;

        return $this;
    }


    /**
     * Инициализация метаданных на основе анотации
     *
     * @param Annotations\EventBusMessage $metadata
     */
    protected function init(Annotations\EventBusMessage $metadata)
    {
        $this->setQueueName($metadata->queue->name);
        $this->setExchangeName($metadata->exchange->name);
        $this->setExchangeType($metadata->exchange->type);

        if (true === $metadata->exchange->durable) {
            $this->setFlagExchangeDurable(true);
        }

        $bindingKeysStorage = [];
        /** @var Annotations\BindingKey[] $bindingKeys */
        $bindingKeys = $metadata->bindingKeys;
        foreach ($bindingKeys as $bindingKey) {
            $bindingKeysStorage[$bindingKey->name] = $bindingKey->name;
        }
        $this->setBindingKeys($bindingKeysStorage);
    }

    /**
     * @return string
     */
    public function getQueueName()
    {
        return $this->queueName;
    }

    /**
     * @param string $queueName
     *
     * @return $this
     */
    public function setQueueName($queueName)
    {
        $this->queueName = (string)$queueName;

        return $this;
    }

    /**
     * @return string
     */
    public function getExchangeName()
    {
        return $this->exchangeName;
    }

    /**
     * @param string $exchangeName
     *
     * @return $this
     */
    public function setExchangeName($exchangeName)
    {
        $this->exchangeName = (string)$exchangeName;

        return $this;
    }

    /**
     * @return array
     */
    public function getBindingKeys()
    {
        return $this->bindingKeys;
    }

    /**
     * @param array $bindingKeys
     *
     * @return $this
     */
    public function setBindingKeys(array $bindingKeys = [])
    {
        $this->bindingKeys = array_unique($bindingKeys);

        return $this;
    }
}
