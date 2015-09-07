<?php
/**
 * @link https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\Driver;

/**
 * Class ConnectionDriverTrait
 *
 * @package OldTown\EventBus\Driver
 */
interface ConnectionDriverInterface
{
    /**
     * @return array
     */
    public function getConnectionConfig();

    /**
     * @param array $connectionConfig
     *
     * @return $this
     */
    public function setConnectionConfig(array $connectionConfig = []);
}
