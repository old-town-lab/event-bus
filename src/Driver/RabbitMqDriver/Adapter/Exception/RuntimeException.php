<?php
/**
 * @link https://github.com/old-town/event-buss
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\Driver\RabbitMqDriver\Adapter\Exception;

use OldTown\EventBus\Driver\RabbitMqDriver\Exception\RuntimeException as Exception;

/**
 * Class RuntimeException
 *
 * @package OldTown\EventBus\Driver\RabbitMqDriver\Adapter\Exception
 */
class RuntimeException extends Exception implements
    ExceptionInterface
{
}
