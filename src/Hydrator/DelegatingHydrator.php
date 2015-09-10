<?php
/**
 * @link https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\Hydrator;

use Zend\Stdlib\Hydrator\HydratorInterface;


/**
 * Class DelegatingHydrator
 * @package OldTown\EventBus\Hydrator
 */
class DelegatingHydrator implements HydratorInterface
{
    /**
     * {@inheritdoc}
     *
     * @throws Exception\DelegateObjectNotFoundException
     */
    public function hydrate(array $data, $object)
    {
        if (!$object instanceof HydratorInterface) {
            $errMsg = sprintf('Объект которому делегируется гидрация должен реализовывать %s', HydratorInterface::class);
            throw new Exception\DelegateObjectNotFoundException($errMsg);
        }
        return $object->hydrate($data, $object);
    }

    /**
     * {@inheritdoc}
     */
    public function extract($object)
    {
        if (!$object instanceof HydratorInterface) {
            $errMsg = sprintf(
                'Объект которому делегируется извлечение данных из объекта должен реализовывать %s',
                HydratorInterface::class
            );
            throw new Exception\DelegateObjectNotFoundException($errMsg);
        }
        return $object->extract($object);
    }
}
