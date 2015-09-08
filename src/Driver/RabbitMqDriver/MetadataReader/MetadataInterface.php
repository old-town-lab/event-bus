<?php
/**
 * @link https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\Driver\RabbitMqDriver\MetadataReader;

use OldTown\EventBus\MetadataReader\MetadataInterface as BaseMetadataInterface;

/**
 * Interface MetadataInterface
 *
 * @package OldTown\EventBus\Driver\RabbitMqDriver\MetadataReader
 */
interface MetadataInterface extends BaseMetadataInterface
{
    /**
     * @return bool|null
     */
    public function getFlagExchangeDurable();

    /**
     * @param bool|null $flagExchangeDurable
     * @return $this
     */
    public function setFlagExchangeDurable($flagExchangeDurable = null);


    /**
     * @return string
     */
    public function getExchangeType();

    /**
     * @param string $exchangeType
     * @return $this
     */
    public function setExchangeType($exchangeType);

    /**
     * @return string
     */
    public function getQueueName();

    /**
     * @param string $queueName
     *
     * @return $this
     */
    public function setQueueName($queueName);

    /**
     * @return string
     */
    public function getExchangeName();

    /**
     * @param string $exchangeName
     *
     * @return $this
     */
    public function setExchangeName($exchangeName);

    /**
     * @return array
     */
    public function getBindingKeys();

    /**
     * @param array $bindingKeys
     *
     * @return $this
     */
    public function setBindingKeys(array $bindingKeys = []);
}
