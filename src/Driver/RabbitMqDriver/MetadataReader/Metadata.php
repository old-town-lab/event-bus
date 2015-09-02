<?php
/**
 * @link https://github.com/old-town/event-buss
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBuss\Driver\RabbitMqDriver\MetadataReader;

/**
 * Class Metadata
 *
 * @package OldTown\EventBuss\Driver\RabbitMqDriver\MetadataReader
 */
class Metadata
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
     * @param Annotations\EventBussMessage $metadata
     */
    public function __construct(Annotations\EventBussMessage $metadata)
    {
        $this->init($metadata);
    }

    /**
     * Инициализация метаданных на основе анотации
     *
     * @param Annotations\EventBussMessage $metadata
     */
    protected function init(Annotations\EventBussMessage $metadata)
    {
        $this->setQueueName($metadata->queue->name);
        $this->setExchangeName($metadata->exchange->name);

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
