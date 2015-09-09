<?php
/**
 * @link    https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\PhpUnit\TestData\Messages;

use OldTown\EventBus\Driver\RabbitMqDriver\MetadataReader\Annotations as EventBus;
use OldTown\EventBus\Message\AbstractSimpleMessage;

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
 *             name="*.procedure.*"
 *         ),
 *         @EventBus\BindingKey(
 *             name="create.procedure.*"
 *         )
 *     }
 * )
 *
 */
class FooExtendAbstractSimpleMessage extends AbstractSimpleMessage
{

}
