<?php
/**
 * @link https://github.com/old-town/event-buss
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBuss\EventBussManager;

use OldTown\EventBuss\Driver\EventBussDriverInterface;
use OldTown\EventBuss\Message\MessageInterface;


/**
 * Class EventBussManagerFacade
 *
 * @package OldTown\EventBuss\EventBussManagerFacade
 */
class EventBussManagerFacade implements EventBussManagerInterface
{
    /**
     * @var EventBussDriverInterface
     */
    protected $driver;

    /**
     * @param EventBussDriverInterface $driver
     */
    public function __construct(EventBussDriverInterface $driver)
    {
        $this->setDriver($driver);
    }

    /**
     * @return EventBussDriverInterface
     */
    public function getDriver()
    {
        return $this->driver;
    }

    /**
     * @param EventBussDriverInterface $driver
     *
     * @return $this
     */
    public function setDriver(EventBussDriverInterface $driver)
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
