<?php
/**
 * @link https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\PhpUnit\Test\Message;

use OldTown\EventBus\Message\ClassMethodsHydratorTrait;
use OldTown\EventBus\PhpUnit\TestData\Messages\AbstractFooClassMethodsHydrator;
use OldTown\EventBus\PhpUnit\TestData\Messages\BarClassMethodsHydrator;
use PHPUnit_Framework_TestCase;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\Stdlib\Hydrator\Filter\FilterComposite;
use Zend\Stdlib\Hydrator\HydratorPluginManager;
use Zend\Validator\ValidatorPluginManager;
use Zend\Stdlib\Hydrator\ClassMethods;
use OldTown\EventBus\Hydrator\DelegatingHydrator;

/**
 * Class ClassMethodsHydratorTraitTest
 *
 * @package OldTown\EventBus\PhpUnit\Test\Message
 */
class ClassMethodsHydratorTraitTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var BarClassMethodsHydrator
     */
    protected $message;


    /**
     * Получение фильтра
     *
     */
    public function testGetFilter()
    {
        $filter = $this->message->getFilter();
        static::assertInstanceOf(FilterComposite::class, $filter);

        //Тест локального кеширования
        $expected = $this->message->getFilter();
        $actual = $this->message->getFilter();
        static::assertEquals($expected, $actual);
    }

    /**
     * Тест получения списка ресурсов методы которых исключаются гидратором
     *
     */
    public function testGetTargetsForExcludeMethods()
    {
        $actual = array_keys($this->message->getTargetsForExcludeMethods());

        $expected = [
            ClassMethodsHydratorTrait::class,
            AbstractFooClassMethodsHydrator::class,
            ServiceLocatorAwareTrait::class
        ];

        static::assertEquals(count($expected), count($actual));
        static::assertEmpty(array_diff($actual, $expected));
    }

    /**
     * Тест локального кеша метода getExcludeMethods
     *
     */
    public function testGetExcludeMethodsLocalCache()
    {
        $expected = $this->message->getExcludeMethods();
        $actual   = $this->message->getExcludeMethods();

        static::assertEquals($expected, $actual);
    }


    /**
     * Тест локального кеша метода getTargetsForExcludeMethods
     *
     */
    public function testGetTargetsForExcludeMethodsLocalCache()
    {
        $expected = $this->message->getTargetsForExcludeMethods();
        $actual   = $this->message->getTargetsForExcludeMethods();

        static::assertEquals($expected, $actual);
    }

    /**
     * Проверяем что работает функционал позволяющий добавить в исключение ресурс(класс, интерфейс, трейт). После чего
     * методы ресурса будут участоввать в гидрации
     *
     */
    public function testGetExcludeMethodsSkippSource()
    {
        //Провреяем что в методе корректно отрабатывает функционал исключащий
        $this->message->addExcludedMethodSource(ServiceLocatorAwareTrait::class);

        $excludeMethods = $this->message->getExcludeMethods();

        $allMethods = get_class_methods(BarClassMethodsHydrator::class);

        $diff = array_diff($allMethods, $excludeMethods);

        $expected = [
            'getProperty1',
            'setProperty1',
            'getProperty2',
            'setProperty2',
            'getProperty3',
            'setProperty3',
            'setServiceLocator',
            'getServiceLocator'
        ];

        static::assertEmpty(array_diff($diff, $expected));

        $expected =  $this->message->getExcludeMethods();
        $actual =  $this->message->getExcludeMethods();

        static::assertEquals($expected, $actual);
    }


    /**
     * Проверка получения названия методов исключаемых при работе гидратора
     */
    public function testGetExcludeMethods()
    {
        $excludeMethods = $this->message->getExcludeMethods();

        $allMethods = get_class_methods(BarClassMethodsHydrator::class);

        $diff = array_diff($allMethods, $excludeMethods);

        $expected = [
            'getProperty1',
            'setProperty1',
            'getProperty2',
            'setProperty2',
            'getProperty3',
            'setProperty3'
        ];

        static::assertEmpty(array_diff($diff, $expected));

        $expected =  $this->message->getExcludeMethods();
        $actual =  $this->message->getExcludeMethods();

        static::assertEquals($expected, $actual);
    }

    /**
     *
     * @return void
     */
    protected function setUp()
    {
        $hydratorPluginManager = new HydratorPluginManager();
        $validatorPluginManager = new ValidatorPluginManager();

        $hydratorPluginManager->setService(DelegatingHydrator::class, new DelegatingHydrator());


        $this->message = new BarClassMethodsHydrator($hydratorPluginManager, $validatorPluginManager);
    }


    /**
     * Проверка корректности инициализации гидратора
     *
     */
    public function testInitHydrtor()
    {
        $hydrator = $this->message->getHydrator();
        static::assertInstanceOf(ClassMethods::class, $hydrator);

        static::assertNull($hydrator->getNamingStrategy());
    }

    /**
     * Тестирование добавления нового ресурса (класс,метод, интерфейс) методы которого не должны обрабыватьваться гидратором
     *
     */
    public function testAddTargetForExcludeMethods()
    {
        static::assertEquals($this->message, $this->message->addTargetForExcludeMethods(BarClassMethodsHydrator::class));

        $actual = array_keys($this->message->getTargetsForExcludeMethods());

        $expected = [
            ClassMethodsHydratorTrait::class,
            AbstractFooClassMethodsHydrator::class,
            ServiceLocatorAwareTrait::class,
            BarClassMethodsHydrator::class
        ];

        static::assertEmpty(array_diff($expected, $actual));
    }


    /**
     * Тестирование удаление  ресурса (класс,метод, интерфейс) методы которого не должны обрабыватьваться гидратором
     *
     */
    public function testRemoveTargetForExcludeMethods()
    {
        static::assertTrue($this->message->removeTargetForExcludeMethods(ClassMethodsHydratorTrait::class));
        static::assertTrue($this->message->removeTargetForExcludeMethods(AbstractFooClassMethodsHydrator::class));
        static::assertTrue($this->message->removeTargetForExcludeMethods(ServiceLocatorAwareTrait::class));
        static::assertFalse($this->message->removeTargetForExcludeMethods('abrakadabra'));

        $actual = $this->message->getTargetsForExcludeMethods();

        static::assertEmpty($actual);
    }

    /**
     * Тестирование удаление  ресурса (класс,метод, интерфейс) из списка исключений
     *
     */
    public function testRemoveExcludedMethodSource()
    {
        static::assertFalse($this->message->removeExcludedMethodSource('abrakadabra'));
        $this->message->addExcludedMethodSource('abrakadabra');
        static::assertTrue($this->message->removeExcludedMethodSource('abrakadabra'));
    }


    /**
     * Тестирование фильтра
     *
     */
    public function testExcludeAbstractMessageMethodFilter()
    {
        static::assertFalse($this->message->excludeAbstractMessageMethodFilter('Test::getServiceLocator'));
        static::assertFalse($this->message->excludeAbstractMessageMethodFilter('getServiceLocator'));
        static::assertTrue($this->message->excludeAbstractMessageMethodFilter('abrakadabra'));
    }
}
