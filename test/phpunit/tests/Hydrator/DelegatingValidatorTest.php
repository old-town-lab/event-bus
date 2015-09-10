<?php
/**
 * @link https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\PhpUnit\Test\Hydrator;

use PHPUnit_Framework_TestCase;
use PHPUnit_Framework_MockObject_MockObject;
use OldTown\EventBus\Hydrator\DelegatingHydrator;
use Zend\Stdlib\Hydrator\HydratorInterface;


/**
 * Class DelegatingHydratorTest
 * @package OldTown\EventBus\PhpUnit\Test\Hydrator
 */
class DelegatingHydratorTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var DelegatingHydrator
     */
    protected $hydrator;

    /**
     * @var HydratorInterface|PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockDelegateObject;

    /**
     * @inheritDoc
     */
    protected function setUp()
    {
        $this->hydrator = new DelegatingHydrator();
        $this->mockDelegateObject = $this->getMock(HydratorInterface::class, get_class_methods(HydratorInterface::class));
    }


    /**
     * Проверка ситуации когда гидрируется некорректный объект
     *
     * @expectedException \OldTown\EventBus\Hydrator\Exception\DelegateObjectNotFoundException
     * @expectedExceptionMessage Объект которому делегируется гидрация должен реализовывать Zend\Stdlib\Hydrator\HydratorInterface
     */
    public function testHydrateInvalidObject()
    {
        $this->hydrator->hydrate([], new \stdClass());
    }


    /**
     * Проверка ситуации когда извлекатются данные из некорректного объекта
     *
     * @expectedException \OldTown\EventBus\Hydrator\Exception\DelegateObjectNotFoundException
     * @expectedExceptionMessage Объект которому делегируется извлечение данных из объекта должен реализовывать Zend\Stdlib\Hydrator\HydratorInterface
     */
    public function testExtractInvalidObject()
    {
        $this->hydrator->extract(new \stdClass());
    }


    /**
     * Извлечение данных из объекта
     *
     */
    public function testExtract()
    {
        $expected = [
            'key_1' => 'value_1',
            'key_2' => 'value_2',
            'key_3' => 'value_3'
        ];

        $this->mockDelegateObject
             ->expects(static::once())
             ->method('extract')
             ->with(static::equalTo($this->mockDelegateObject))
             ->will(static::returnValue($expected));

        $actual = $this->hydrator->extract($this->mockDelegateObject);

        static::assertEquals($expected, $actual);
    }


    /**
     * Заполнение объекта из данных
     *
     */
    public function testHydrate()
    {
        $expectedHydrateData = [
            'key_1' => 'value_1',
            'key_2' => 'value_2',
            'key_3' => 'value_3'
        ];

        $this->mockDelegateObject
            ->expects(static::once())
            ->method('hydrate')
            ->with(static::equalTo($expectedHydrateData), static::equalTo($this->mockDelegateObject))
            ->will(static::returnValue($this->mockDelegateObject));

        $actual = $this->hydrator->hydrate($expectedHydrateData, $this->mockDelegateObject);

        static::assertEquals($this->mockDelegateObject, $actual);
    }
}
