<?php
/**
 * @link https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\Message;

use Zend\Validator\ValidatorInterface;


/**
 * Трейт используется в том случае если функция валидации данных пришедших из шины сообщений реализована в классе
 * сообщения
 *
 * Class AbstractMessage
 *
 * @package OldTown\EventBus\Message
 */
trait SelfValidatorTrait
{
    /**
     * @var ValidatorInterface
     */
    protected $validator;

    /**
     * Возвращает валидатор для проверки десириализованных данных
     *
     * @return ValidatorInterface
     * @throws Exception\InvalidValidatorException
     */
    public function getValidator()
    {
        if ($this->validator) {
            return $this->validator;
        }

        if (!$this instanceof ValidatorInterface) {
            $errMsg = sprintf('Сообщение должно имплементировать %s', ValidatorInterface::class);
            throw new Exception\InvalidValidatorException($errMsg);
        }
        /** @var SelfValidatorTrait $this */
        $this->validator = $this;

        return $this->validator;
    }



    /**
     * Устанавливает валидатор для проверки десириализованных данных
     *
     * @param ValidatorInterface $validator
     *
     * @return $this
     */
    public function setValidator(ValidatorInterface $validator)
    {
        $this->validator = $validator;

        return $this;
    }
}
