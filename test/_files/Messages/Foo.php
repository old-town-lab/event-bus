<?php
/**
 * @link    https://github.com/old-town/event-buss
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBuss\TestData\Messages;

use OldTown\EventBuss\Driver\RabbitMqDriver\MetadataReader\Annotations as EventBuss;


/**
 * Class Foo
 *
 * @package OldTown\EventBuss\TestData\Messages
 *
 * @EventBuss\EventBussMessage(
 *     queue=@EventBuss\Queue(name="test_queue_name"),
 *     exchange=@EventBuss\Exchange(name="test_exchange_name"),
 *     bindingKeys={
 *         @EventBuss\BindingKey(
 *             name="test_binding_key_1"
 *         ),
 *         @EventBuss\BindingKey(
 *             name="test_binding_key_2"
 *         )
 *     }
 * )
 *
 */
class Foo
{

}
