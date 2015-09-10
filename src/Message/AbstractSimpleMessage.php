<?php
/**
 * @link https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\Message;

use Zend\Stdlib\Hydrator\Filter\FilterProviderInterface;
use Zend\Validator\ValidatorInterface;

abstract class AbstractSimpleMessage extends AbstractMessage implements FilterProviderInterface, ValidatorInterface
{
    use ClassMethodsHydratorTrait;

    public function isValid($value)
    {
        // TODO: Implement isValid() method.
    }

    public function getMessages()
    {
        // TODO: Implement getMessages() method.
    }
}
