<?php
/**
 * @link https://github.com/old-town/event-buss
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\MetadataReader;

/**
 * Interface EventBussMetadataReaderProviderInterface
 *
 * @package OldTown\EventBus\MetadataReader
 */
interface EventBussMetadataReaderProviderInterface
{
    /**
     * @return array
     */
    public function getEventBussMetadataReaderConfig();
}
