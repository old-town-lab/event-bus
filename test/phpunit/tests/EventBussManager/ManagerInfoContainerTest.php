<?php
/**
 * @link https://github.com/old-town/event-buss
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBuss\PhpUnit\Test\EventBussManager;

use OldTown\EventBuss\EventBussManager\ManagerInfoContainer;
use PHPUnit_Framework_TestCase;


/**
 * Class ManagerInfoContainerTest
 *
 * @package OldTown\EventBuss\PhpUnit\Test\EventBussManagerFacade
 */
class ManagerInfoContainerTest extends PHPUnit_Framework_TestCase
{
    /**
     * Создаем ManagerInfoContainer с пустым конфигом
     *
     * @expectedException \OldTown\EventBuss\EventBussManager\Exception\InvalidEventBussManagerConfigException
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
