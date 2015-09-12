<?php
/**
 * @link    https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\PhpUnit\TestData\Messages;

use OldTown\EventBus\Message\AbstractMessage;
use OldTown\EventBus\Message\ClassMethodsHydratorInterface;
use OldTown\EventBus\Message\InputFilterPluginManagerAwareInterface;
use OldTown\EventBus\Message\InputFilterPluginManagerAwareTrait;
use OldTown\EventBus\Message\InputFilterValidatorTrait;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterPluginManager;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

abstract class AbstractFooClassMethodsHydrator
    extends AbstractMessage
    implements
        ClassMethodsHydratorInterface,
        ServiceLocatorAwareInterface,
        InputFilterProviderInterface,
        InputFilterAwareInterface,
        InputFilterPluginManagerAwareInterface
{
    use ServiceLocatorAwareTrait, InputFilterValidatorTrait, InputFilterPluginManagerAwareTrait;

}
