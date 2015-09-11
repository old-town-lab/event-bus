<?php
/**
 * @link    https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\PhpUnit\TestData\SimpleMessage;

use OldTown\EventBus\Message\AbstractSimpleMessage;
use Zend\InputFilter\InputFilterProviderInterface;

/**
 * Class Foo
 *
 * @package OldTown\EventBus\PhpUnit\TestData\SimpleMessage
 */
class Foo extends AbstractSimpleMessage implements InputFilterProviderInterface
{
    /**
     * @var string
     */
    protected $testProperty1 = 'abrakadabra';

    /**
     * @var boolean
     */
    protected $testProperty2 = false;

    /**
     * @var array
     */
    protected $testProperty3 = [];


    /**
     * @return string
     */
    public function getTestProperty1()
    {
        return $this->testProperty1;
    }

    /**
     * @param string $testProperty1
     *
     * @return $this
     */
    public function setTestProperty1($testProperty1 = null)
    {
        $this->testProperty1 = $testProperty1;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getTestProperty2()
    {
        return $this->testProperty2;
    }

    /**
     * @param boolean $testProperty2
     *
     * @return $this
     */
    public function setTestProperty2($testProperty2 = null)
    {
        $this->testProperty2 = $testProperty2;

        return $this;
    }

    /**
     * @return array
     */
    public function getTestProperty3()
    {
        return $this->testProperty3;
    }

    /**
     * @param array $testProperty3
     *
     * @return $this
     */
    public function setTestProperty3($testProperty3 = null)
    {
        $this->testProperty3 = $testProperty3;

        return $this;
    }

    /**
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'testProperty1' => $this->testProperty1,
            'testProperty2' => $this->testProperty2,
            'testProperty3' => $this->testProperty3
        ];
    }

    /**
     * @return array
     */
    public function getInputFilterSpecification()
    {
        $spec = [
            'testProperty1' => [
                'required' => true,
                'allow_empty' => false,
                'filters' => [
                    [
                        'name' => 'StringTrim',
                    ]
                ],
                'validators' => [
                    [
                        'name' => 'StringLength',
                        'options' => [
                            'min' => 1,
                            'max' => 255,
                        ]
                    ]
                ]
            ],
            'testProperty2' => [
                'required' => true,
                'allow_empty' => true,
                'validators' => [
                    [
                        'name' => 'Callback',
                        'options' => [
                            'callback' => function($values) {
                                return is_bool($values);
                            }
                        ]
                    ]
                ]
            ],
            'testProperty3' => [
                'required' => true,
                'allow_empty' => true,
                'validators' => [
                    [
                        'name' => 'Callback',
                        'options' => [
                            'callback' => function($values) {
                                return is_array($values);
                            }
                        ]
                    ]
                ]
            ],



        ];



        return $spec;
    }


}
