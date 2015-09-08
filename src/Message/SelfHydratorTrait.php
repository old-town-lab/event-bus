<?php
/**
 * @link https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\Message;

use OldTown\EventBus\Message\Exception\InvalidHydratorException;
use Zend\Stdlib\Hydrator\HydratorInterface;

/**
 * Трейт используется в том случае если функция гирдации данных реализована в классе
 * сообщения
 *
 * Class AbstractMessage
 *
 * @package OldTown\EventBus\Message
 */
trait SelfHydratorTrait
{
    /**
     * @var HydratorInterface
     */
    protected $hydrator;

    /**
     * Set hydrator
     *
     * @param  HydratorInterface $hydrator
     *
     * @return $this
     */
    public function setHydrator(HydratorInterface $hydrator)
    {
        $this->hydrator = $hydrator;

        return $this;
    }

    /**
     * Retrieve hydrator
     *
     * @return HydratorInterface
     *
     * @throws InvalidHydratorException
     */
    public function getHydrator()
    {
        if ($this->hydrator) {
            return $this->hydrator;
        }

        if (!$this instanceof HydratorInterface) {
            $errMsg = sprintf('Сообщение должно имлементировать %s', HydratorInterface::class);
            throw new Exception\InvalidHydratorException($errMsg);
        }

        /** @var SelfHydratorTrait $this */
        $this->hydrator = $this;

        return $this->hydrator;
    }
}
