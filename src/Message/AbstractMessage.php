<?php
/**
 * @link https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\Message;

use Zend\Validator\ValidatorInterface;
use Zend\Stdlib\Hydrator\HydratorInterface;
use Zend\Stdlib\Hydrator\HydratorPluginManager;
use Zend\Serializer\Adapter\AdapterInterface as Serializer;
use Zend\Serializer\Serializer as SerializerFactory;
use OldTown\EventBus\Validator\DelegatingValidator;
use Zend\Validator\ValidatorPluginManager;
use OldTown\EventBus\Hydrator\DelegatingHydrator;

/**
 * Class AbstractMessage
 *
 * @package OldTown\EventBus\Message
 */
abstract class AbstractMessage implements MessageInterface
{
    /**
     * @var HydratorPluginManager
     */
    protected $hydratorPluginManager;

    /**
     * @var ValidatorPluginManager
     */
    protected $validatorPluginManager;

    /**
     * @var ValidatorInterface
     */
    protected $validator;

    /**
     * Имя Serializer используемого для упаковки/распаковки тела сообщения
     *
     * @var string
     */
    protected $serializerName = 'json';

    /**
     * Опиции для настройки Serializer
     *
     * @var array
     */
    protected $serializerOptions = [];

    /**
     * Serializer используемый для упаковки распаковки сообщений
     *
     * @var Serializer
     */
    protected $serializer;

    /**
     * @var HydratorInterface
     */
    protected $hydrator;

    /**
     * Имя гидратора используемого по умолчанию
     *
     * @var string
     */
    protected $hydratorName = DelegatingHydrator::class;

    /**
     * Опиции для настройки гидратора
     *
     * @var array
     */
    protected $hydratorOptions = [];

    /**
     * @var array
     */
    protected $validatorOptions = [];

    /**
     * Имя валидатора используемого по умолчанию
     *
     * @var string
     */
    protected $validatorName = DelegatingValidator::class;

    /**
     * @param HydratorPluginManager  $hydratorPluginManager
     * @param ValidatorPluginManager $validatorPluginManager
     */
    public function __construct(HydratorPluginManager $hydratorPluginManager, ValidatorPluginManager $validatorPluginManager)
    {
        $this->setHydratorPluginManager($hydratorPluginManager);
        $this->setValidatorPluginManager($validatorPluginManager);

        $validatorOptions = [
            DelegatingValidator::DELEGATE_OBJECT => $this
        ];
        $this->setValidatorOptions($validatorOptions);

        $this->initMessage();
    }

    /**
     * Инициация сообщения
     *
     *
     * @return void
     */
    protected function initMessage()
    {
        $this->initHydrator();
        $this->initValidator();
        $this->init();
    }

    /**
     * Инициализация гидратора
     *
     * @return void
     */
    protected function initHydrator()
    {
    }


    /**
     * Инициализация валидатора
     *
     * @return void
     */
    protected function initValidator()
    {
    }


    /**
     * Инициализация класса
     *
     * @return void
     */
    protected function init()
    {
    }

    /**
     * @return HydratorPluginManager
     *
     */
    public function getHydratorPluginManager()
    {
        return $this->hydratorPluginManager;
    }

    /**
     * @param HydratorPluginManager $hydratorPluginManager
     *
     * @return $this
     */
    public function setHydratorPluginManager(HydratorPluginManager $hydratorPluginManager)
    {
        $this->hydratorPluginManager = $hydratorPluginManager;

        return $this;
    }

    /**
     * @return ValidatorPluginManager
     */
    public function getValidatorPluginManager()
    {
        return $this->validatorPluginManager;
    }

    /**
     * @param ValidatorPluginManager $validatorPluginManager
     *
     * @return $this
     */
    public function setValidatorPluginManager(ValidatorPluginManager $validatorPluginManager)
    {
        $this->validatorPluginManager = $validatorPluginManager;

        return $this;
    }


    /**
     * Получает Serializer используемый для упаковки распаковки сообщений
     *
     * @return Serializer
     */
    public function getSerializer()
    {
        if ($this->serializer) {
            return $this->serializer;
        }
        $name = $this->getSerializerName();
        $options = $this->getSerializerOptions();

        $this->serializer = SerializerFactory::factory($name, $options);

        return $this->serializer;
    }

    /**
     * @return HydratorInterface
     *
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     * @throws \Zend\ServiceManager\Exception\RuntimeException
     * @throws \Zend\ServiceManager\Exception\ServiceNotCreatedException
     *
     */
    public function getHydrator()
    {
        if ($this->hydrator) {
            return $this->hydrator;
        }
        $name = $this->getHydratorName();
        $options = $this->getHydratorOptions();

        $this->hydrator = $this->getHydratorPluginManager()->get($name, $options);

        return $this->hydrator;
    }


    /**
     * @param HydratorInterface $hydrator
     *
     * @return $this
     */
    public function setHydrator(HydratorInterface $hydrator)
    {
        $this->hydrator = $hydrator;

        return $this;
    }

    /**
     * @return ValidatorInterface
     *
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     * @throws \Zend\ServiceManager\Exception\RuntimeException
     * @throws \Zend\ServiceManager\Exception\ServiceNotCreatedException
     */
    public function getValidator()
    {
        if ($this->validator) {
            return $this->validator;
        }
        $name = $this->getValidatorName();
        $options = $this->getValidatorOptions();

        $this->validator = $this->getValidatorPluginManager()->get($name, $options);

        return $this->validator;
    }

    /**
     * @param ValidatorInterface $validator
     *
     * @return $this
     */
    public function setValidator(ValidatorInterface $validator)
    {
        $this->validator = $validator;

        return $this;
    }



    /**
     * @return array
     */
    public function getHydratorOptions()
    {
        return $this->hydratorOptions;
    }

    /**
     * @param array $hydratorOptions
     *
     * @return $this
     */
    public function setHydratorOptions(array $hydratorOptions = [])
    {
        $this->hydratorOptions = $hydratorOptions;

        return $this;
    }

    /**
     * @param Serializer $serializer
     *
     * @return $this
     */
    public function setSerializer(Serializer $serializer)
    {
        $this->serializer = $serializer;

        return $this;
    }


    /**
     * Возвращает  имя Serializer используемого для упаковки/распаковки тела сообщения
     *
     * @return string
     */
    public function getSerializerName()
    {
        return $this->serializerName;
    }

    /**
     * Устанавливает Имя Serializer используемого для упаковки/распаковки тела сообщения
     *
     * @param string $serializerName
     *
     * @return $this
     */
    public function setSerializerName($serializerName)
    {
        $this->serializerName = (string)$serializerName;

        return $this;
    }

    /**
     * @return string
     */
    public function getHydratorName()
    {
        return $this->hydratorName;
    }

    /**
     * @param string $hydratorName
     *
     * @return $this
     */
    public function setHydratorName($hydratorName)
    {
        $this->hydratorName = (string)$hydratorName;

        return $this;
    }

    /**
     * @return string
     */
    public function getValidatorName()
    {
        return $this->validatorName;
    }

    /**
     * @param string $validatorName
     *
     * @return $this
     */
    public function setValidatorName($validatorName)
    {
        $this->validatorName = (string)$validatorName;

        return $this;
    }

    /**
     * @return array
     */
    public function getValidatorOptions()
    {
        return $this->validatorOptions;
    }

    /**
     * @param array $validatorOptions
     *
     * @return $this
     */
    public function setValidatorOptions(array $validatorOptions = [])
    {
        $this->validatorOptions = $validatorOptions;

        return $this;
    }



    /**
     * @return array
     */
    public function getSerializerOptions()
    {
        return $this->serializerOptions;
    }

    /**
     * @param array $serializerOptions
     *
     * @return $this
     */
    public function setSerializerOptions(array $serializerOptions = [])
    {
        $this->serializerOptions = $serializerOptions;

        return $this;
    }



    /**
     * Получить контент для отправки сообещния
     *
     * @return string
     *
     * @throws \Zend\Serializer\Exception\ExceptionInterface
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     * @throws \Zend\ServiceManager\Exception\RuntimeException
     * @throws \Zend\ServiceManager\Exception\ServiceNotCreatedException
     */
    public function getContent()
    {
        $data = $this->getHydrator()->extract($this);
        $stringMessage = $this->getSerializer()->serialize($data);

        return $stringMessage;
    }



    /**
     * @param $serializedData
     *
     * @throws \OldTown\EventBus\Message\Exception\DataForMessageNotValidException
     * @throws \Zend\Validator\Exception\RuntimeException
     * @throws \Zend\Serializer\Exception\ExceptionInterface
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     * @throws \Zend\ServiceManager\Exception\RuntimeException
     * @throws \Zend\ServiceManager\Exception\ServiceNotCreatedException
     *
     * @return $this
     */
    public function setContent($serializedData)
    {
        $data = $this->getSerializer()->unserialize($serializedData);
        $validator = $this->getValidator();
        if (!$validator->isValid($data)) {
            throw new Exception\DataForMessageNotValidException($validator->getMessages());
        }
        $this->getHydrator()->hydrate($data, $this);

        return $this;
    }

    /**
     * Set metadata
     *
     * @param  string|int|array|\Traversable $spec
     * @param  mixed $value
     */
    public function setMetadata($spec, $value = null)
    {
    }

    /**
     * Get metadata
     *
     * @param  null|string|int $key
     * @return mixed
     */
    public function getMetadata($key = null)
    {
    }
}
