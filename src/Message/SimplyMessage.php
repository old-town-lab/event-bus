<?php
/**
 * @link https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\Message;

use Zend\Stdlib\Hydrator\HydratorInterface;
use Zend\Validator\ValidatorInterface;


/**
 * Class AbstractMessage
 *
 * @package OldTown\EventBus\Message
 */
class SimplyMessage extends AbstractMessage implements ValidatorInterface, HydratorInterface
{
    use SelfHydratorTrait, SelfValidatorTrait;

    /**
     * @param mixed $object
     *
     * @return array
     */
    public function extract($object)
    {
    }

    /**
     * @param array  $data
     * @param mixed $object
     *
     * @return Object
     */
    public function hydrate(array $data, $object)
    {
    }

    /**
     * @param mixed $value
     *
     * @return boolean
     */
    public function isValid($value)
    {
    }

    /**
     *
     * @return string
     */
    public function getMessages()
    {
    }
}
