<?php
/**
 * @link https://github.com/old-town/event-buss
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBuss\Driver;


/**
 * Interface EventBussDriverProviderInterface
 *
 * @package OldTown\EventBuss\Driver
 */
interface EventBussDriverProviderInterface
{
    /**
     * @return array
     */
    public function getEventBussDriverConfig();
}
