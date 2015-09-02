<?php
/**
 * @link https://github.com/old-town/event-buss
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBuss\Driver\RabbitMqDriver\MetadataReader\Exception;

use \OldTown\EventBuss\MetadataReader\Exception\RuntimeException as Exception;

/**
 * Class RuntimeException
 *
 * @package OldTown\EventBuss\Driver\RabbitMqDriver\MetadataReader\Exception
 */
class RuntimeException extends Exception implements
    ExceptionInterface
{
}
