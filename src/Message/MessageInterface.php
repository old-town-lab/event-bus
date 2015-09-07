<?php
/**
 * @link https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\Message;

use \Zend\Stdlib\MessageInterface as BaseMessageInterface;

/**
 * Interface MessageInterface
 * @package OldTown\EventBus\Message
 */
interface MessageInterface extends BaseMessageInterface
{
    /**
     * Подготовка данных для отправки
     *
     * @return string
     */
    public function serialize();

    /**
     * Подготовка данных для заполения объекта сообещния
     *
     * @param $string
     *
     * @return Object
     */
    public function unserialize($string);

    /**
     * @return bool
     */
    public function validate();
}
