<?php
/**
 * @link https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\Validator;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\MutableCreationOptionsInterface;
use Zend\ServiceManager\MutableCreationOptionsTrait;
use Zend\ServiceManager\ServiceLocatorInterface;

class DelegatingValidatorFactory implements FactoryInterface, MutableCreationOptionsInterface
{
    use MutableCreationOptionsTrait;

    /**
     * Creates DelegatingHydrator
     *
     * @param  ServiceLocatorInterface $serviceLocator
     * @return DelegatingValidator
     *
     * @throws  Exception\DelegateObjectNotFoundException
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $options = $this->getCreationOptions();

        if (!array_key_exists(DelegatingValidator::DELEGATE_OBJECT, $options)) {
            $errMsg = 'Отсутствует объект для делегирования';
            throw new Exception\DelegateObjectNotFoundException($errMsg);
        }

        $options = [
            DelegatingValidator::DELEGATE_OBJECT => $options[DelegatingValidator::DELEGATE_OBJECT]
        ];
        return new DelegatingValidator($options);
    }
}
