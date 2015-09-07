<?php
/**
 * @link https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\Message\Exception;

use OldTown\EventBus\Exception\RuntimeException as Exception;

/**
 * Class RuntimeException
 *
 * @package OldTown\EventBus\Message\Exception
 */
class RuntimeException extends Exception implements
    ExceptionInterface
{
}
