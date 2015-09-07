<?php
/**
 * @link https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\MetadataReader;

/**
 * Interface EventBusMetadataReaderProviderInterface
 *
 * @package OldTown\EventBus\MetadataReader
 */
interface EventBusMetadataReaderProviderInterface
{
    /**
     * @return array
     */
    public function getEventBusMetadataReaderConfig();
}
