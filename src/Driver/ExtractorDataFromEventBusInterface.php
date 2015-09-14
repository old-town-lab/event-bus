<?php
/**
 * @link https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\Driver;

/**
 * Interface ExtractorDataFromEventBusInterface
 *
 * @package OldTown\EventBus\Driver
 */
interface ExtractorDataFromEventBusInterface extends EventBusDriverInterface
{
    /**
     * На основе данных пришедших из очереди, извлекат тип Serializer, которым эти данные упкаованы
     *
     * @param array $rawData
     *
     * @return string
     */
    public function extractSerializerName(array $rawData = []);

    /**
     * На основе данных пришедших из очереди, извлекат сериализованные данные
     *
     * @param array $rawData
     *
     * @return string
     */
    public function extractSerializedData(array $rawData = []);
}
