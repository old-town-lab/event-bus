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
     * @param mixed $messageData
     *
     * @return string
     */
    public function serialize($messageData);

    /**
     * Подготовка данных для заполения объекта сообещния
     *
     * @param $string
     *
     * @return mixed
     */
    public function unserialize($string);

    /**
     * @param mixed $data
     *
     * @return bool
     */
    public function validate($data);

    /**
     * @return mixed
     */
    public function hydrate();

    /**
     * @param array $data
     *
     * @return mixed
     */
    public function populateValues(array $data = []);

}


