<?php
/**
 * @link https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\PhpUnit\Test\Validator;

use PHPUnit_Framework_TestCase;
use OldTown\EventBus\Validator\DelegatingValidatorFactory;
use OldTown\EventBus\Validator\DelegatingValidator;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Validator\ValidatorInterface;

/**
 * Class DelegatingValidatorFactoryTest
 * @package OldTown\EventBus\PhpUnit\Test\Validator
 */
class DelegatingValidatorFactoryTest extends PHPUnit_Framework_TestCase
{
    /**
     * Проверка работы фабрики по созданию валидатора делегирующего функции валидации другому объекту
     */
    public function testCreateService()
    {
        $factory = new DelegatingValidatorFactory();

        $creationOptions = [
            DelegatingValidator::DELEGATE_OBJECT => $this->getMock(ValidatorInterface::class)
        ];
        $factory->setCreationOptions($creationOptions);

        /** @var ServiceLocatorInterface $serviceLocatorMock */
        $serviceLocatorMock = $this->getMock(ServiceLocatorInterface::class);

        $actual = $factory->createService($serviceLocatorMock);

        static::assertInstanceOf(DelegatingValidator::class, $actual);
    }

    /**
     * Проверка работы фабрики для случая когда не передан объект для делигации
     *
     * @expectedException \OldTown\EventBus\Validator\Exception\DelegateObjectNotFoundException
     * @expectedExceptionMessage Отсутствует объект для делегирования
     */
    public function testCreateServiceInvalidDelegateObject()
    {
        $factory = new DelegatingValidatorFactory();

        /** @var ServiceLocatorInterface $serviceLocatorMock */
        $serviceLocatorMock = $this->getMock(ServiceLocatorInterface::class);

        $factory->createService($serviceLocatorMock);
    }
}
