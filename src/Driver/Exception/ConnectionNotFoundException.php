<?php
/**
 * @link https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\Driver\Exception;

use OldTown\EventBus\Exception\RuntimeException as Exception;

/**
 * Class RuntimeException
 *
 * @package OldTown\EventBus\Driver\Exception
 */
class ConnectionNotFoundException extends Exception implements
    ExceptionInterface
{
}
