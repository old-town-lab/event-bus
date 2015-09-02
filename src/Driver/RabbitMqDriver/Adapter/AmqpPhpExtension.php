<?php
/**
 * @link https://github.com/old-town/event-buss
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBuss\Driver\RabbitMqDriver\Adapter;

use OldTown\EventBuss\Driver\RabbitMqDriver\MetadataReader\Metadata;

/**
 * Class AmqpPhpExtension
 *
 * @package OldTown\EventBuss\Driver\RabbitMqDriver\Adapter
 */
class AmqpPhpExtension extends AbstractAdapter
{
    /**
     * @var
     */
    protected $connection;



    /**
     * Инициализация шины
     *
     * @param Metadata[] $metadata
     */
    public function initEventBuss($metadata)
    {
    }
}
