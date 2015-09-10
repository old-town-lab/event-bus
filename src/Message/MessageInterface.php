<?php
/**
 * @link https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\Message;

use Zend\Stdlib\Hydrator\HydratorInterface;
use Zend\Stdlib\Hydrator\HydratorPluginManager;
use Zend\Stdlib\MessageInterface as BaseMessageInterface;
use Zend\Serializer\Adapter\AdapterInterface as Serializer;
use Zend\Stdlib\Hydrator\HydratorAwareInterface;
use \Zend\Validator\ValidatorInterface;
use Zend\Validator\ValidatorPluginManager;

/**
 * Interface MessageInterface
 * @package OldTown\EventBus\Message
 */
interface MessageInterface extends BaseMessageInterface, HydratorAwareInterface
{
    /**
     * @return HydratorPluginManager
     *
     */
    public function getHydratorPluginManager();

    /**
     * @param HydratorPluginManager $hydratorPluginManager
     *
     * @return $this
     */
    public function setHydratorPluginManager(HydratorPluginManager $hydratorPluginManager);

    /**
     * @return ValidatorPluginManager
     */
    public function getValidatorPluginManager();

    /**
     * @param ValidatorPluginManager $validatorPluginManager
     *
     * @return $this
     */
    public function setValidatorPluginManager(ValidatorPluginManager $validatorPluginManager);


    /**
     * Получает Serializer используемый для упаковки распаковки сообщений
     *
     * @return Serializer
     */
    public function getSerializer();

    /**
     * @return HydratorInterface
     *
     *
     */
    public function getHydrator();


    /**
     * @param HydratorInterface $hydrator
     *
     * @return $this
     */
    public function setHydrator(HydratorInterface $hydrator);

    /**
     * @return ValidatorInterface
     *
     */
    public function getValidator();

    /**
     * @param ValidatorInterface $validator
     *
     * @return $this
     */
    public function setValidator(ValidatorInterface $validator);



    /**
     * @return array
     */
    public function getHydratorOptions();

    /**
     * @param array $hydratorOptions
     *
     * @return $this
     */
    public function setHydratorOptions(array $hydratorOptions = []);

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
     * @return string
     */
    public function getHydratorName();

    /**
     * @param string $hydratorName
     *
     * @return $this
     */
    public function setHydratorName($hydratorName);

    /**
     * @return string
     */
    public function getValidatorName();

    /**
     * @param string $validatorName
     *
     * @return $this
     */
    public function setValidatorName($validatorName);

    /**
     * @return array
     */
    public function getValidatorOptions();

    /**
     * @param array $validatorOptions
     *
     * @return $this
     */
    public function setValidatorOptions(array $validatorOptions = []);



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
     * Получить контент для отправки сообещния
     *
     * @return string
     *
     */
    public function getContent();

    /**
     * @param $serializedData
     *
     * @return $this
     */
    public function fromString($serializedData);
}
