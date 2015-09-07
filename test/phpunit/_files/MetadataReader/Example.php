<?php
/**
 * @link    https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\PhpUnit\TestData\MetadataReader;

use OldTown\EventBus\Driver\RabbitMqDriver\MetadataReader\Annotations as EventBus;

/**
 * Class Foo
 *
 * @package OldTown\EventBus\TestData\Messages
 *
 * @EventBus\EventBusMessage(
 *     queue=@EventBus\Queue(name="test_queue_name_foo"),
 *     exchange=@EventBus\Exchange(name="test_exchange_name_foo", type="topic", durable=true),
 *     bindingKeys={
 *         @EventBus\BindingKey(
 *             name="test_binding_key_1"
 *         ),
 *         @EventBus\BindingKey(
 *             name="test_binding_key_2"
 *         )
 *     }
 * )
 *
 */
class Example
{

}
