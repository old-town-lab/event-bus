<?php
/**
 * @link https://github.com/old-town/event-buss
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBuss\Driver;

use OldTown\EventBuss\Message\MessageInterface;


/**
 * Interface EventBussDriverInterface
 *
 * @package OldTown\EventBuss\Driver
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
