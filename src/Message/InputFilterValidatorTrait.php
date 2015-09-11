<?php
/**
 * @link https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\Message;

use Zend\InputFilter\CollectionInputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\Factory as InputFilterFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\InputFilter\InputInterface;
use Zend\InputFilter\ReplaceableInputInterface;

/**
 * Class InputFilterValidatorTrait
 *
 * @package OldTown\EventBus\Message
 */
trait InputFilterValidatorTrait
{
    /**
     * @var InputFilterFactory
     */
    protected $inputFilterFactory;

    /**
     * @var InputFilterInterface
     */
    protected $dataInputFilter;

    /**
     * Сообщения о ошибках валидации
     *
     * @var array
     */
    protected $messages;

    /**
     * Инициация валидатора
     *
     * @return void
     */
    protected function initValidator()
    {
        if ($this instanceof ClassMethodsHydratorInterface) {
            /** @var ClassMethodsHydratorInterface $this  */
            if ($this instanceof InputFilterProviderInterface) {
                $this->addTargetForExcludeMethods(InputFilterProviderInterface::class);
            }
            if ($this instanceof InputFilterAwareInterface) {
                $this->addTargetForExcludeMethods(InputFilterAwareInterface::class);
            }
        }
    }

    /**
     * Создает/возвращает фабрику InputFilter'jов
     *
     * @return InputFilterFactory
     */
    public function getInputFilterFactory()
    {
        if ($this->inputFilterFactory) {
            return $this->inputFilterFactory;
        }
        $inputFilterManager = null;
        if ($this instanceof InputFilterPluginManagerAwareInterface) {
            $inputFilterManager = $this->getInputFilterPluginManager();
        }
        $factory = new InputFilterFactory($inputFilterManager);
        $this->inputFilterFactory = $factory;

        return $this->inputFilterFactory;
    }

    /**
     * Устанавливает фабрику InputFilter'jов
     *
     * @param InputFilterFactory $inputFilterFactory
     *
     * @return $this
     */
    public function setInputFilterFactory(InputFilterFactory $inputFilterFactory)
    {
        $this->inputFilterFactory = $inputFilterFactory;

        return $this;
    }

    /**
     * @return InputFilterInterface
     *
     * @throws \Zend\InputFilter\Exception\InvalidArgumentException
     * @throws \Zend\Filter\Exception\InvalidArgumentException
     * @throws \Zend\Filter\Exception\RuntimeException
     * @throws \OldTown\EventBus\Message\Exception\RuntimeException
     */
    public function getDataInputFilter()
    {
        if ($this->dataInputFilter) {
            return $this->dataInputFilter;
        }
        $this->dataInputFilter = new InputFilter();
        $result = $this->attachInputFilterDefaults($this->dataInputFilter, $this);
        if (!$result) {
            $errMsg = 'Нет InputFilter\'ов';
            throw new Exception\RuntimeException($errMsg);
        }


        return $this->dataInputFilter;
    }



    /**
     * Attach defaults provided by the elements to the input filter
     *
     * @param  InputFilterInterface $inputFilter
     * @param  $message
     * @return boolean
     *
     * @throws \Zend\InputFilter\Exception\InvalidArgumentException
     * @throws \Zend\Filter\Exception\InvalidArgumentException
     * @throws \Zend\Filter\Exception\RuntimeException
     */
    public function attachInputFilterDefaults(InputFilterInterface $inputFilter, $message)
    {
        $flag = false;
        if ($message instanceof InputFilterAwareInterface) {
            $input = $message->getInputFilter();
            $this->addInputFilter($inputFilter, $input);
            $flag = true;
        }

        if ($message instanceof InputFilterProviderInterface) {
            $inputFactory = $this->getInputFilterFactory();
            $inputFilterSpecification = $message->getInputFilterSpecification();

            foreach ($inputFilterSpecification as $name => $spec) {
                $input = $inputFactory->createInput($spec);
                $this->addInputFilter($inputFilter, $input, $name);
                $flag = true;
            }
        }

        return $flag;
    }

    /**
     * @param InputFilterInterface $inputFilter
     * @param InputFilterInterface $input
     * @param string $name
     *
     * @throws \Zend\InputFilter\Exception\InvalidArgumentException
     */
    protected function addInputFilter($inputFilter, $input, $name = null)
    {
        /** @var ReplaceableInputInterface|InputFilterInterface|CollectionInputFilter $inputFilter */
        /** @var InputFilterInterface|InputInterface $input */
        if ($inputFilter instanceof ReplaceableInputInterface && $inputFilter->has($name)) {
            $input->merge($inputFilter->get($name));
            $inputFilter->replace($input, $name);
        } elseif ($inputFilter instanceof CollectionInputFilter && !$inputFilter->getInputFilter()->has($name)) {
            $inputFilter->getInputFilter()->add($input, $name);
        } else {
            $inputFilter->add($input, $name);
        }
    }

    /**
     * @param InputFilterInterface $dataInputFilter
     *
     * @return $this
     */
    public function setDataInputFilter(InputFilterInterface $dataInputFilter)
    {
        $this->dataInputFilter = $dataInputFilter;

        return $this;
    }


    /**
     * @param $value
     *
     * @return bool
     *
     * @throws \Zend\InputFilter\Exception\InvalidArgumentException
     * @throws \Zend\Filter\Exception\InvalidArgumentException
     * @throws \Zend\Filter\Exception\RuntimeException
     * @throws \OldTown\EventBus\Message\Exception\RuntimeException
     */
    public function isValid($value)
    {
        $filter = $this->getDataInputFilter();
        $filter->setData($value);
        $filter->setValidationGroup(InputFilterInterface::VALIDATE_ALL);

        $isValid = $filter->isValid();

        if (!$isValid) {
            $this->messages = $filter->getMessages();
        }

        return $isValid;
    }

    /**
     * @return array
     */
    public function getMessages()
    {
        return $this->messages;
    }
}
