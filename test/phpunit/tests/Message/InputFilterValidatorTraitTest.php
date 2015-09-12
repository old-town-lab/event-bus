<?php
/**
 * @link https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\PhpUnit\Test\Message;

use OldTown\EventBus\PhpUnit\TestData\Messages\BarClassMethodsHydrator;
use OldTown\EventBus\Validator\DelegatingValidator;
use OldTown\EventBus\Validator\DelegatingValidatorFactory;
use PHPUnit_Framework_TestCase;
use Zend\Stdlib\Hydrator\HydratorPluginManager;
use Zend\Validator\ValidatorPluginManager;


/**
 * Class InputFilterValidatorTraitTest
 *
 * @package OldTown\EventBus\PhpUnit\Test\Message
 */
class InputFilterValidatorTraitTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var BarClassMethodsHydrator
     */
    protected $message;


    /**
     *
     * @return void
     */
    protected function setUp()
    {
        $hydratorPluginManager = new HydratorPluginManager();
        $validatorPluginManager = new ValidatorPluginManager();

        $validatorPluginManager->setFactory(DelegatingValidator::class, new DelegatingValidatorFactory());


        $this->message = new BarClassMethodsHydrator($hydratorPluginManager, $validatorPluginManager);
    }

    /**
     * Проверка того что настройка гидратора происходит корректно
     */
    public function testInitHydrator()
    {
    }
}
