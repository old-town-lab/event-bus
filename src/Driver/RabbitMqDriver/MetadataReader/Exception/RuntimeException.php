<?php
/**
 * @link https://github.com/old-town/event-buss
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\Driver\RabbitMqDriver\MetadataReader\Exception;

use \OldTown\EventBus\MetadataReader\Exception\RuntimeException as Exception;

/**
 * Class RuntimeException
 *
 * @package OldTown\EventBus\Driver\RabbitMqDriver\MetadataReader\Exception
 */
class RuntimeException extends Exception implements
    ExceptionInterface
{
}
