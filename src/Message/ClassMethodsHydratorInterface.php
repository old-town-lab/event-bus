<?php
/**
 * @link https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\Message;

use Zend\Stdlib\Hydrator\Filter\FilterInterface;
use Zend\Stdlib\Hydrator\ClassMethods;
use ReflectionClass;

/**
 * Важно! Для того что бы trait работал, необходимо что бы класс сообщения мплементировал интерфейс \Zend\Stdlib\Hydrator\Filter\FilterProviderInterface
 *
 *
 * Class ClassMethodsHydratorTrait
 *
 * @package OldTown\EventBus\Message
 */
interface ClassMethodsHydratorInterface
{
    /**
     * @param string $hydratorName
     *
     * @return $this
     */
    public function setHydratorName($hydratorName);

    /**
     * @return ClassMethods
     *
     */
    public function getHydrator();

    /**
     * Возвращает фильтр полей используемых при гидрации
     *
     * @return FilterInterface
     *
     * @throws \Zend\Stdlib\Exception\InvalidArgumentException
     */
    public function getFilter();

    /**
     * Ислючает из гидрации метода описанные в AbstractMessage
     *
     * @param string $property
     *
     * @return bool
     */
    public function excludeAbstractMessageMethodFilter($property);

    /**
     * Возвращает методы которые не должны учитываться гидратором
     *
     * Находит среди потомков класса, первый класс который является абстрактым (
     *
     * @return array
     */
    public function getExcludeMethods();

    /**
     * Возвращает массив ключами которого являются имена классов/трейтов/интрефейсов методы которых должны быть исключены
     * при работе гидратора, а значением объект рефлекссии для данного класса/трейта/интрефейса
     *
     * @return ReflectionClass[]
     */
    public function getTargetsForExcludeMethods();

    /**
     * Добавялет класс/трейт/интрефейс методы которого должны быть исключены при работе гидратора
     *
     * @param $target
     *
     * @return $this
     */
    public function addTargetForExcludeMethods($target);

    /**
     * Удаляет класс/трейт/интрефейс методы которого должны быть исключены при работе гидратора
     *
     * @param $target
     *
     * @return boolean
     */
    public function removeTargetForExcludeMethods($target);
}
