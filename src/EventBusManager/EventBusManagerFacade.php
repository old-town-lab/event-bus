<?php
/**
 * @link https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\EventBusManager;

use OldTown\EventBus\Driver\EventBusDriverInterface;
use OldTown\EventBus\Message\MessageInterface;


/**
 * Class EventBusManagerFacade
 *
 * @package OldTown\EventBus\EventBusManagerFacade
 */
class EventBusManagerFacade implements EventBusManagerInterface
{
    /**
     * @var EventBusDriverInterface
     */
    protected $driver;

    /**
     * @param EventBusDriverInterface $driver
     */
    public function __construct(EventBusDriverInterface $driver)
    {
        $this->setDriver($driver);
    }

    /**
     * @return EventBusDriverInterface
     */
    public function getDriver()
    {
        return $this->driver;
    }

    /**
     * @param EventBusDriverInterface $driver
     *
     * @return $this
     */
    public function setDriver(EventBusDriverInterface $driver)
    {
        $this->driver = $driver;

        return $this;
    }

    /**
     * Бросает событие
     *
     * @param string $eventName
     * @param MessageInterface $message
     */
    public function trigger($eventName, MessageInterface $message)
    {
        $this->getDriver()->trigger($eventName, $message);
    }

    /**
     * Принимает событие
     *
     * @param MessageInterface $message
     * @param $callBack
     */
    public function listener(MessageInterface $message, $callBack)
    {
    }
}
