<?php
/**
 * @link https://github.com/old-town/event-buss
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBuss\MetadataReader;

/**
 * Interface EventBussMetadataReaderProviderInterface
 *
 * @package OldTown\EventBuss\MetadataReader
 */
interface EventBussMetadataReaderProviderInterface
{
    /**
     * @return array
     */
    public function getEventBussMetadataReaderConfig();
}
