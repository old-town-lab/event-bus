<?php
/**
 * @link https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\PhpUnit\Test\Message;

use OldTown\EventBus\Message\EventBusMessagePluginManager;
use PHPUnit_Framework_TestCase;
use OldTown\EventBus\Message\MessagePluginManagerAwareTrait;

/**
 * Class MessagePluginManagerAwareTraitTest
 *
 * @package OldTown\EventBus\PhpUnit\Test\Message
 */
class MessagePluginManagerAwareTraitTest extends PHPUnit_Framework_TestCase
{
    /**
     * Проверка установки/получения плагин менеджера для работы с сообщениями
     */
    public function testGetterSetterMessagePluginManager()
    {
        /** @var EventBusMessagePluginManager $mockMessagePluginManager */
        $mockMessagePluginManager = $this->getMock(EventBusMessagePluginManager::class);
        /** @var MessagePluginManagerAwareTrait $trait */
        $trait = $this->getMockForTrait(MessagePluginManagerAwareTrait::class);

        static::assertEquals($trait, $trait->setMessagePluginManager($mockMessagePluginManager));

        static::assertEquals($mockMessagePluginManager, $trait->getMessagePluginManager());
    }
}
