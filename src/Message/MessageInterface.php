<?php
/**
 * @link https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\Message;

use Zend\Stdlib\MessageInterface as BaseMessageInterface;
use Zend\Serializer\Adapter\AdapterInterface as Serializer;
use Zend\Stdlib\Hydrator\HydratorAwareInterface;
use \Zend\Validator\ValidatorInterface;

/**
 * Interface MessageInterface
 * @package OldTown\EventBus\Message
 */
interface MessageInterface extends BaseMessageInterface, HydratorAwareInterface
{
    /**
     * Получает Serializer используемый для упаковки распаковки сообщений
     *
     * @return Serializer
     */
    public function getSerializer();

    /**
     * @param Serializer $serializer
     *
     * @return $this
     */
    public function setSerializer(Serializer $serializer);


    /**
     * Возвращает  имя Serializer используемого для упаковки/распаковки тела сообщения
     *
     * @return string
     */
    public function getSerializerName();

    /**
     * Устанавливает Имя Serializer используемого для упаковки/распаковки тела сообщения
     *
     * @param string $serializerName
     *
     * @return $this
     */
    public function setSerializerName($serializerName);

    /**
     * @return array
     */
    public function getSerializerOptions();

    /**
     * @param array $serializerOptions
     *
     * @return $this
     */
    public function setSerializerOptions(array $serializerOptions = []);

    /**
     * Возвращает валидатор для проверки десириализованных данных
     *
     * @return ValidatorInterface
     */
    public function getValidator();


    /**
     * Возвращает валидатор для проверки десириализованных данных
     *
     * @param ValidatorInterface $validator
     *
     * @return $this
     */
    public function setValidator(ValidatorInterface $validator);
}
