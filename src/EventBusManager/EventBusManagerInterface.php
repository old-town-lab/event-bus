<?php
/**
 * @link https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\EventBusManager;

use OldTown\EventBus\Driver\EventBusDriverInterface;
use OldTown\EventBus\Message\MessageInterface;

/**
 * Interface EventBusManagerInterface
 *
 * @package OldTown\EventBus\EventBusManagerFacade
 */
interface EventBusManagerInterface
{
    /**
     * @return EventBusDriverInterface
     */
    public function getDriver();

    /**
     * @param EventBusDriverInterface $driver
     *
     * @return $this
     */
    public function setDriver(EventBusDriverInterface $driver);


    /**
     * Бросает событие
     *
     * @param string $eventName
     * @param MessageInterface $message
     */
    public function trigger($eventName, MessageInterface $message);

    /**
     * Принимает событие
     *
     * @param          $messageName
     * @param callable $callBack
     *
     * @return
     * @internal param string $message
     */
    public function attach($messageName, callable $callBack);


    /**
     * Инициализация шины
     *
     * @return void
     *
     */
    public function initEventBus();
}
