<?php
/**
 * @link https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\Driver;

use OldTown\EventBus\Message\MessageInterface;


/**
 * Interface EventBussDriverInterface
 *
 * @package OldTown\EventBus\Driver
 */
interface EventBussDriverInterface
{
    /**
     * @param $eventName
     * @param MessageInterface $message
     */
    public function trigger($eventName, MessageInterface $message);

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
    public function initEventBuss();
}
