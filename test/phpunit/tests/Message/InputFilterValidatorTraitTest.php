<?php
/**
 * @link    https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\PhpUnit\Test\Message;

use OldTown\EventBus\Message\InputFilterValidatorTrait;
use OldTown\EventBus\PhpUnit\TestData\Messages\BarClassMethodsHydrator;
use OldTown\EventBus\Validator\DelegatingValidator;
use OldTown\EventBus\Validator\DelegatingValidatorFactory;
use PHPUnit_Framework_TestCase;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\InputFilter\InputInterface;
use Zend\Stdlib\Hydrator\HydratorPluginManager;
use Zend\Validator\ValidatorPluginManager;
use Zend\InputFilter\Factory as InputFilterFactory;
use PHPUnit_Framework_MockObject_MockObject;


/**
 * Class InputFilterValidatorTraitTest
 *
 * @package OldTown\EventBus\PhpUnit\Test\Message
 */
class InputFilterValidatorTraitTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var BarClassMethodsHydrator
     */
    protected $message;


    /**
     *
     * @return void
     */
    protected function setUp()
    {
        $hydratorPluginManager = new HydratorPluginManager();
        $validatorPluginManager = new ValidatorPluginManager();

        $validatorPluginManager->setFactory(DelegatingValidator::class, new DelegatingValidatorFactory());


        $this->message = new BarClassMethodsHydrator($hydratorPluginManager, $validatorPluginManager);
    }

    /**
     * Проверка того что настройка гидратора происходит корректно
     */
    public function testInitHydrator()
    {
        $actual = $this->message->getTargetsForExcludeMethods();


        static::assertArrayHasKey(InputFilterProviderInterface::class, $actual);
        static::assertArrayHasKey(InputFilterAwareInterface::class, $actual);
    }


    /**
     * Создание фабрики input filter'ов
     */
    public function testGetInputFilterFactory()
    {
        $inputFilterFactory = $this->message->getInputFilterFactory();
        static::assertInstanceOf(InputFilterFactory::class, $inputFilterFactory);
    }


    /**
     * Создание фабрики input filter'ов. Проверка локального кеширования
     */
    public function testGetInputFilterFactoryLocalCache()
    {
        $expected = $this->message->getInputFilterFactory();
        $actual = $this->message->getInputFilterFactory();
        static::assertEquals($expected, $actual);
    }

    /**
     * Проверка получения/установки InputFilterFactory
     */
    public function testGetterSetterInputFilterFactory()
    {
        /** @var InputFilterFactory $mock */
        $mock = $this->getMock(InputFilterFactory::class);

        static::assertEquals($this->message, $this->message->setInputFilterFactory($mock));
        static::assertEquals($mock, $this->message->getInputFilterFactory());
    }


    /**
     * Проверка получения/установки DataInputFilter
     */
    public function testGetterSetterDataInputFilter()
    {
        /** @var InputFilterInterface $mock */
        $mock = $this->getMock(InputFilterInterface::class);

        static::assertEquals($this->message, $this->message->setDataInputFilter($mock));
        static::assertEquals($mock, $this->message->getDataInputFilter());
    }

    /**
     * Проверка получения InputFiler'ов. Ситуация когда не создано ни одного фильтра
     *
     * @expectedException \OldTown\EventBus\Message\Exception\RuntimeException
     * @expectedExceptionMessage Нет InputFilter'ов
     */
    public function testGetDataInputFilterNotInputFilter()
    {
        /** @var InputFilterValidatorTrait  $mock */
        $mock = $this->getMockForTrait(InputFilterValidatorTrait::class);
        $mock->getDataInputFilter();
    }


    /**
     * Проверка получения InputFiler'ов.
     *
     */
    public function testGetDataInputFilter()
    {
        /** @var InputFilterInterface  $mockInputFilterInterface */
        $mockInputFilterInterface = $this->getMock(InputFilterInterface::class);

        $this->message->setInputFilter($mockInputFilterInterface);
        /** @var InputFilter $dataInputFilter */
        $dataInputFilter = $this->message->getDataInputFilter();

        $inputs = $dataInputFilter->getInputs();

        static::assertEquals(1, count($inputs));
        reset($inputs);
        $actual = current($inputs);
        static::assertEquals($mockInputFilterInterface, $actual);
    }

    /**
     * Добавление фильтреов по умолчанию. Объект реализует InputFilterAwareInterface
     *
     */
    public function testAttachInputFilterDefaultsInputFilterAwareInterface()
    {
        $inputFilterMock = $this->getMock(InputFilterInterface::class);


        /** @var InputFilterInterface|PHPUnit_Framework_MockObject_MockObject $baseInputFilterMock */
        $baseInputFilterMock = $this->getMock(InputFilterInterface::class);
        $baseInputFilterMock->expects(static::once())
                            ->method('add')
                            ->with(static::equalTo($inputFilterMock));


        $message = $this->getMock(InputFilterAwareInterface::class, get_class_methods(InputFilterAwareInterface::class));
        $message->expects(static::once())
                ->method('getInputFilter')
                ->will(static::returnValue($inputFilterMock));

        /** @var InputFilterValidatorTrait  $mock */
        $mock = $this->getMockForTrait(InputFilterValidatorTrait::class);

        $result = $mock->attachInputFilterDefaults($baseInputFilterMock, $message);

        static::assertTrue($result);
    }

    /**
     * Добавление фильтреов по умолчанию. Объект реализует InputFilterProviderInterface
     *
     */
    public function testAttachInputFilterDefaultsInputFilterProviderInterface()
    {

        /** @var InputFilterInterface|PHPUnit_Framework_MockObject_MockObject $baseInputFilterMock */
        $baseInputFilterMock = $this->getMock(InputFilterInterface::class);
        $baseInputFilterMock->expects(static::once())
            ->method('add')
            ->with(static::isInstanceOf(InputInterface::class));


        $message = $this->getMock(InputFilterProviderInterface::class, get_class_methods(InputFilterProviderInterface::class));
        $message->expects(static::once())
            ->method('getInputFilterSpecification')
            ->will(static::returnValue([
                'test' => [
                    'required' => true
                ]
            ]));

        /** @var InputFilterValidatorTrait  $mock */
        $mock = $this->getMockForTrait(InputFilterValidatorTrait::class);

        $result = $mock->attachInputFilterDefaults($baseInputFilterMock, $message);

        static::assertTrue($result);
    }



    public function testIsValid()
    {
        $validData = 'test';

        $errorMessage = [
            'code_1' => 'error_message_1',
            'code_2' => 'error_message_2'
        ];


        /** @var InputFilterInterface|PHPUnit_Framework_MockObject_MockObject $filterMock */
        $filterMock = $this->getMock(InputFilterInterface::class, get_class_methods(InputFilterInterface::class));
        $filterMock->expects(static::once())
                   ->method('setData')
                   ->with(static::equalTo($validData));
        $filterMock->expects(static::once())
            ->method('getMessages')
            ->will(static::returnValue($errorMessage));
        $filterMock->expects(static::once())
            ->method('isValid')
            ->will(static::returnValue(false));

        $this->message->setDataInputFilter($filterMock);

        static::assertEquals(false, $this->message->isValid($validData));

        static::assertEquals($errorMessage, $this->message->getMessages());
    }
}
