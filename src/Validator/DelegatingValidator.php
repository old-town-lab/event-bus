<?php
/**
 * @link https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\Validator;

use Zend\Validator\AbstractValidator;
use Zend\Validator\ValidatorInterface;
use Zend\Validator\ValidatorPluginManager;


class DelegatingValidator extends AbstractValidator
{
    /**
     * @var string
     */
    const DELEGATE_OBJECT = 'delegateObject';
    /**
     * @var string
     */
    const VALIDATOR_PLUGIN_MANAGER = 'validatorPluginManager';

    /**
     * @var ValidatorPluginManager
     */
    protected $validatorPluginManager;

    /**
     * @var Object
     */
    protected $delegateObject;

    /**
     * @param mixed $value
     *
     * @return bool
     *
     *
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     * @throws \Zend\ServiceManager\Exception\ServiceNotCreatedException
     * @throws \Zend\ServiceManager\Exception\RuntimeException
     * @throws \Zend\Validator\Exception\RuntimeException
     */
    public function isValid($value)
    {
        $result = $this->getDelegateObject()->isValid($value);

        return $result;
    }

    /**
     * @return array
     *
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     * @throws \Zend\ServiceManager\Exception\ServiceNotCreatedException
     * @throws \Zend\ServiceManager\Exception\RuntimeException
     * @throws \Zend\Validator\Exception\RuntimeException
     */
    public function getMessages()
    {
        $result = $this->getDelegateObject()->getMessages();

        return $result;
    }


    /**
     * @return ValidatorInterface
     */
    public function getDelegateObject()
    {
        if (!$this->delegateObject instanceof ValidatorInterface) {
            $errMsg = 'Отсутствует объект для делегирования';
            throw new Exception\DelegateObjectNotFoundException($errMsg);
        }
        return $this->delegateObject;
    }

    /**
     * @param ValidatorInterface $delegateObject
     *
     * @return $this
     *
     * @throws \OldTown\EventBus\Validator\Exception\DelegateObjectNotFoundException
     */
    public function setDelegateObject(ValidatorInterface $delegateObject)
    {
        $this->delegateObject = $delegateObject;

        return $this;
    }
}
