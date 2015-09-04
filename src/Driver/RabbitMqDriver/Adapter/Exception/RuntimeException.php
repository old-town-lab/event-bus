<?php
/**
 * @link https://github.com/old-town/event-buss
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBuss\Driver\RabbitMqDriver\Adapter\Exception;

use OldTown\EventBuss\Driver\RabbitMqDriver\Exception\RuntimeException as Exception;

/**
 * Class RuntimeException
 *
 * @package OldTown\EventBuss\Driver\RabbitMqDriver\Adapter\Exception
 */
class RuntimeException extends Exception implements
    ExceptionInterface
{
}
