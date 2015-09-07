<?php
/**
 * @link https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\Message;

use \Zend\Stdlib\Message;
use Zend\Serializer\Adapter\AdapterInterface as Serializer;
use Zend\Serializer\Serializer as SerializerFactory;
use Zend\Stdlib\Hydrator\HydratorAwareTrait;


/**
 * Class AbstractMessage
 *
 * @package OldTown\EventBus\Message
 */
abstract class AbstractMessage extends Message implements MessageInterface
{
    use HydratorAwareTrait;

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
     */
    public function getContent()
    {
    }
}
