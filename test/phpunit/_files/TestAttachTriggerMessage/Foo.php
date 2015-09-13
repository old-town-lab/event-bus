<?php
/**
 * @link    https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\PhpUnit\TestData\TestAttachTriggerMessage;

use OldTown\EventBus\Driver\RabbitMqDriver\MetadataReader\Annotations as EventBus;
use OldTown\EventBus\Message\AbstractSimpleMessage;
use Zend\InputFilter\InputFilterProviderInterface;

/**
 * Class FooExtendSimpleMessage
 *
 * @package OldTown\EventBus\TestData\Messages
 *
 * @EventBus\EventBusMessage(
 *     queue=@EventBus\Queue(name="test_queue_name_foo"),
 *     exchange=@EventBus\Exchange(name="test_exchange_name_foo", type="topic"),
 *     bindingKeys={
 *         @EventBus\BindingKey(
 *             name="#"
 *         )
 *     }
 * )
 *
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
