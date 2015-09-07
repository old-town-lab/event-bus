<?php
/**
 * @link https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\PhpUnit\Test\EventBusManager;

use OldTown\EventBus\EventBusManager\ManagerInfoContainer;
use PHPUnit_Framework_TestCase;


/**
 * Class ManagerInfoContainerTest
 *
 * @package OldTown\EventBus\PhpUnit\Test\EventBusManagerFacade
 */
class ManagerInfoContainerTest extends PHPUnit_Framework_TestCase
{
    /**
     * Создаем ManagerInfoContainer с пустым конфигом
     *
     * @expectedException \OldTown\EventBus\EventBusManager\Exception\InvalidEventBusManagerConfigException
     * @expectedExceptionMessage Отсутствует секция driver
     */
    public function testConfigContainsSectionDriver()
    {
        new ManagerInfoContainer();
    }

    /**
     * Создаем ManagerInfoContainer с пустым конфигом
     *
     */
    public function testSetPluginName()
    {
        $expected = 'example';
        $managerInfoContainer = new ManagerInfoContainer([
            ManagerInfoContainer::PLUGIN_NAME => $expected,
            ManagerInfoContainer::DRIVER => 'default'
        ]);


        static::assertEquals($expected, $managerInfoContainer->getPluginName());
    }
}
