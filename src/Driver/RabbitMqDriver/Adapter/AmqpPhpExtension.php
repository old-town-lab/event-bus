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
     * Имя расширения используемого для взаимодействия с сервером очередей
     *
     * @var string
     */
    protected static $amqpPhpExtensionName = 'amqp';

    /**
     * @param array $connection
     *
     * @throws Exception\AmqpPhpExtensionNotInstalledException
     */
    public function __construct(array $connection = [])
    {
        if (!extension_loaded(static::$amqpPhpExtensionName)) {
            $errMsg = sprintf('Для работы драйвера необходимо php расширение %s', static::$amqpPhpExtensionName);
            throw new Exception\AmqpPhpExtensionNotInstalledException($errMsg);
        }
        parent::__construct($connection);
    }


    /**
     * Инициализация шины
     *
     * @param Metadata[] $metadata
     */
    public function initEventBuss($metadata)
    {
    }
}
