<?php
/**
 * @link https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\Message;

use Zend\Stdlib\Hydrator\Filter\FilterComposite;
use \Zend\Stdlib\Hydrator\Filter\FilterInterface;
use Zend\Stdlib\Hydrator\Filter\GetFilter;
use Zend\Stdlib\Hydrator\Filter\HasFilter;
use Zend\Stdlib\Hydrator\Filter\IsFilter;
use Zend\Stdlib\Hydrator\Filter\OptionalParametersFilter;
use \Zend\Stdlib\Hydrator\ClassMethods;

/**
 * Важно! Для того что бы trait работал, необходимо что бы класс сообщения мплементировал интерфейс \Zend\Stdlib\Hydrator\Filter\FilterProviderInterface
 *
 *
 * Class ClassMethodsHydratorTrait
 *
 * @package OldTown\EventBus\Message
 */
trait ClassMethodsHydratorTrait
{
    /**
     * @var \Zend\Filter\FilterInterface
     */
    protected $classMethodsHydratorFilter;

    /**
     * Методы из AbstractMessage
     *
     * @var array
     */
    protected $excludeMethods;

    /**
     * Список родительских классов,  а также трейтов, методы которых не должны быть исключены.
     * При необходимости добавить именна классов в классе в который встроен данный трейт.
     *
     * @var array
     */
    protected $excludedMethodSource = [];

    /**
     * @param string $hydratorName
     *
     * @return $this
     */
    abstract public function setHydratorName($hydratorName);

    /**
     * @return ClassMethods
     *
     */
    abstract public function getHydrator();

    /**
     * Инициализация гидратора
     *
     * @return void
     */
    protected function initHydrator()
    {
        $this->setHydratorName('classMethods');
        $this->getHydrator()->removeNamingStrategy();
    }


    /**
     * Возвращает фильтр полей используемых при гидрации
     *
     * @return FilterInterface
     *
     * @throws \Zend\Stdlib\Exception\InvalidArgumentException
     */
    public function getFilter()
    {
        if ($this->classMethodsHydratorFilter) {
            return $this->classMethodsHydratorFilter;
        }

        $filter = new FilterComposite();
        $filter->addFilter('excludeAbstractMessageMethod', [$this, 'excludeAbstractMessageMethodFilter'], FilterComposite::CONDITION_AND);
        //$filter->addFilter('is', new IsFilter());
        //$filter->addFilter('has', new HasFilter());
        $filter->addFilter('get', new GetFilter());
        $filter->addFilter('parameter', new OptionalParametersFilter(), FilterComposite::CONDITION_AND);

        $this->classMethodsHydratorFilter = $filter;

        return $this->classMethodsHydratorFilter;
    }

    /**
     * Ислючает из гидрации метода описанные в AbstractMessage
     *
     * @param string $property
     *
     * @return bool
     */
    public function excludeAbstractMessageMethodFilter($property)
    {
        $excludeMethods = $this->getExcludeMethods();
        $pos = strpos($property, '::');
        if ($pos !== false) {
            $pos += 2;
        } else {
            $pos = 0;
        }

        $method = substr($property, $pos);

        $flag = !in_array($method, $excludeMethods, true);
        return $flag;
    }

    /**
     * Возвращает методы которые не должны учитываться гидратором
     *
     * Находит среди потомков класса, первый класс который является абстрактым (
     *
     * @return array
     */
    public function getExcludeMethods()
    {
        if ($this->excludeMethods) {
            return $this->excludeMethods;
        }

        $r = $rCurrent = new \ReflectionClass(static::class);

        $candidateTargetClass = null;

        do {
            $candidateTargetClass = $r;
            $r = $r->getParentClass();
        } while (false !== $r && !$candidateTargetClass->isAbstract());

        $targetClass = $candidateTargetClass->isAbstract() ? $candidateTargetClass : $rCurrent;

        $targets = $targetClass->getTraits();
        $targets[] = $targetClass;

        /** @var \ReflectionClass[] $targets */

        $excludeMethods = [];
        foreach ($targets as $target) {
            if (in_array($target->getName(), $this->excludedMethodSource, true)) {
                continue;
            }
            $methods = $target->getMethods();
            foreach ($methods as $method) {
                $methodName = $method->getName();
                $excludeMethods[$methodName] = $methodName;
            }
        }
        $excludeMethods = $this->filterExcludedMethod($excludeMethods);
        $this->excludeMethods = $excludeMethods;

        return $this->excludeMethods;
    }

    /**
     * Если возникает необходимость скорректрировать список методов исключаемых из hydrate/extract необходимо в конечном
     * классе организовать фильтрацию с помощью даннного метода
     *
     * @param array $excludeMethods
     *
     * @return array
     */
    protected function filterExcludedMethod(array $excludeMethods = [])
    {
        return $excludeMethods;
    }
}
