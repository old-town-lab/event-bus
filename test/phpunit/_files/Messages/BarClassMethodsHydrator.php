<?php
/**
 * @link    https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\PhpUnit\TestData\Messages;

use OldTown\EventBus\Message\ClassMethodsHydratorTrait;
use Zend\InputFilter\InputFilterAwareTrait;
use OldTown\EventBus\Driver\RabbitMqDriver\MetadataReader\Annotations as EventBus;


/**
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
 *
 * Class BarClassMethodsHydrator
 *
 * @package OldTown\EventBus\PhpUnit\TestData\Messages
 */
class BarClassMethodsHydrator extends AbstractFooClassMethodsHydrator
{
    use ClassMethodsHydratorTrait, InputFilterAwareTrait;

    /**
     * @var string
     */
    protected $property1 = 'test';


    /**
     * @var array
     */
    protected $property2 = [];



    /**
     * @var integer
     */
    protected $property3 = 3;

    /**
     * @return string
     */
    public function getProperty1()
    {
        return $this->property1;
    }

    /**
     * @param string $property1
     *
     * @return $this
     */
    public function setProperty1($property1)
    {
        $this->property1 = $property1;

        return $this;
    }

    /**
     * @return array
     */
    public function getProperty2()
    {
        return $this->property2;
    }

    /**
     * @param array $property2
     *
     * @return $this
     */
    public function setProperty2($property2)
    {
        $this->property2 = $property2;

        return $this;
    }

    /**
     * @return int
     */
    public function getProperty3()
    {
        return $this->property3;
    }

    /**
     * @param int $property3
     *
     * @return $this
     */
    public function setProperty3($property3)
    {
        $this->property3 = $property3;

        return $this;
    }


    public function getInputFilterSpecification()
    {
        return [];
    }

}
