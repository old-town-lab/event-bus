<?php
/**
 * @link https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\Message;

use Zend\Stdlib\Hydrator\Filter\FilterComposite;
use Zend\Stdlib\Hydrator\Filter\FilterInterface;
use Zend\Stdlib\Hydrator\Filter\GetFilter;
use Zend\Stdlib\Hydrator\Filter\OptionalParametersFilter;
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
     * Ключем является имя класса/трейта/интрефейса методы которого должны быть исключены при работе гидратора, а значением
     * объект рефлекссии для данного класса/трейта/интрефейса
     *
     * @var array
     */
    protected $targetsForExcludeMethods;

    /**
     * Флаг указывающий на то что сформирован список ресурсов(классов, трейтов, интерфейсов) методы которых не должны браться
     * при рабботе гидратора
     *
     * @var bool
     */
    protected $flagInitTargetsForExcludeMethods = false;

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
     * Добавить в исключения ресурс(класс,трейт, интерфейс). После этого методы ресурса будут участвовать в работе гидратора
     *
     * @param string $excludedMethodSource
     *
     * @return $this
     */
    public function addExcludedMethodSource($excludedMethodSource)
    {
        $excludedMethodSource = (string)$excludedMethodSource;

        $this->excludedMethodSource[$excludedMethodSource] = $excludedMethodSource;
        $this->excludeMethods = null;

        return $this;
    }

    /**
     * Удалить из исключений ресурс
     *
     * @param $excludedMethodSource
     *
     * @return bool
     */
    public function removeExcludedMethodSource($excludedMethodSource)
    {
        if (array_key_exists($excludedMethodSource, $this->excludedMethodSource)) {
            unset($this->excludedMethodSource[$excludedMethodSource]);
            return true;
        }
        return false;
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

        $targets = $this->getTargetsForExcludeMethods();

        /** @var \ReflectionClass[] $targets */

        $excludeMethods = [];
        foreach ($targets as $target) {
            if (array_key_exists($target->getName(), $this->excludedMethodSource)) {
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
     * Возвращает массив ключами которого являются имена классов/трейтов/интрефейсов методы которых должны быть исключены
     * при работе гидратора, а значением объект рефлекссии для данного класса/трейта/интрефейса
     *
     * @return ReflectionClass[]
     */
    public function getTargetsForExcludeMethods()
    {
        if ($this->targetsForExcludeMethods) {
            return $this->targetsForExcludeMethods;
        }
        $this->initTargetsForExcludeMethods();


        return $this->targetsForExcludeMethods;
    }

    /**
     * Получение списка ресурсов(классы/трейты) методы которых исключаются при работе гидратора
     *
     * @return void
     */
    protected function initTargetsForExcludeMethods()
    {
        if ($this->flagInitTargetsForExcludeMethods) {
            return;
        }
        $r = $rCurrent = new ReflectionClass(static::class);

        $candidateTargetClass = null;

        do {
            $candidateTargetClass = $r;
            $r = $r->getParentClass();
        } while (false !== $r && !$candidateTargetClass->isAbstract());

        $targetClass = $candidateTargetClass->isAbstract() ? $candidateTargetClass : $rCurrent;

        $targetTraits = $targetClass->getTraits();
        $targets = [];
        foreach ($targetTraits as $targetTrait) {
            $targets[$targetTrait->getName()] = $targetTrait;
        }
        if (!array_key_exists(ClassMethodsHydratorTrait::class, $targets)) {
            $selfTrait = new ReflectionClass(ClassMethodsHydratorTrait::class);
            $targets[$selfTrait->getName()] = $selfTrait;
        }
        $targets[$targetClass->getName()] = $targetClass;

        $this->targetsForExcludeMethods = $targets;

        $this->flagInitTargetsForExcludeMethods = true;
    }

    /**
     * Добавялет класс/трейт/интрефейс методы которого должны быть исключены при работе гидратора
     *
     * @param $target
     *
     * @return $this
     */
    public function addTargetForExcludeMethods($target)
    {
        $this->initTargetsForExcludeMethods();
        $r = new ReflectionClass($target);

        $this->targetsForExcludeMethods[$r->getName()] = $r;

        return $this;
    }

    /**
     * Удаляет класс/трейт/интрефейс методы которого должны быть исключены при работе гидратора
     *
     * @param $target
     *
     * @return boolean
     */
    public function removeTargetForExcludeMethods($target)
    {
        $this->initTargetsForExcludeMethods();
        if (array_key_exists($target, $this->targetsForExcludeMethods)) {
            unset($this->targetsForExcludeMethods[$target]);
            return true;
        }
        return false;
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
