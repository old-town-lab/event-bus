<?php
/**
 * @link https://github.com/old-town/event-buss
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBuss\Driver;

/**
 * Class ConnectionDriverTrait
 *
 * @package OldTown\EventBuss\Driver
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
