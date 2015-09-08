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
class DummyMessage extends AbstractMessage implements ValidatorInterface, HydratorInterface
{
    use SelfHydratorTrait, SelfValidatorTrait;

    /**
     * @var array
     */
    protected $data = [];

    /**
     * Возвращает данные
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Устанавливает данные
     *
     * @param array $data
     *
     * @return $this
     */
    public function setData(array $data = [])
    {
        $this->data = $data;

        return $this;
    }


    /**
     * @param DummyMessage $object
     *
     * @return array
     *
     * @throws Exception\InvalidArgumentException
     */
    public function extract($object)
    {
        if ($object !== $this) {
            $errMsg = 'Некорректный объект';
            throw new Exception\InvalidArgumentException($errMsg);
        }

        $data = $object->getData();

        return $data;
    }

    /**
     * @param array  $data
     * @param DummyMessage $object
     *
     * @return DummyMessage
     *
     * @throws Exception\InvalidArgumentException
     */
    public function hydrate(array $data, $object)
    {
        if ($object !== $this) {
            $errMsg = 'Некорректный объект';
            throw new Exception\InvalidArgumentException($errMsg);
        }

        $object->setData($data);

        return  $object;
    }

    /**
     * @param mixed $value
     *
     * @return boolean
     */
    public function isValid($value)
    {
        return true;
    }

    /**
     *
     * @return string
     */
    public function getMessages()
    {
    }
}
