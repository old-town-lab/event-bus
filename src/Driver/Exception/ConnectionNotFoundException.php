<?php
/**
 * @link https://github.com/old-town/event-buss
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBuss\Driver\Exception;

use OldTown\EventBuss\Exception\RuntimeException as Exception;

/**
 * Class RuntimeException
 *
 * @package OldTown\EventBuss\Driver\Exception
 */
class ConnectionNotFoundException extends Exception implements
    ExceptionInterface
{
}
