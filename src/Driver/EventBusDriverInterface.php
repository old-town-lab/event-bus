<?php
/**
 * @link    https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\Driver;

use OldTown\EventBus\Message\MessageInterface;


/**
 * Interface EventBusDriverInterface
 *
 * @package OldTown\EventBus\Driver
 */
interface EventBusDriverInterface
{
    /**
     * @param                  $eventName
     * @param MessageInterface $message
     */
    public function trigger($eventName, MessageInterface $message);

    /**
     * Подписывается на прием сообщений
     *
     * @param string   $messageName
     * @param callable $callback
     */
    public function attach($messageName, callable $callback);

    /**
     * Опции переданные при создание драйвера
     *
     * @return array
     */
    public function getDriverOptions();

    /**
     * Устанавливает опции
     *
     * @param array $options
     *
     * @return $this
     */
    public function setOptions(array $options = []);

    /**
     * Инициализация шины
     *
     * @return void
     */
    public function initEventBus();
}
