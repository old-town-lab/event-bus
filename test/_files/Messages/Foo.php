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
 *     queue=@EventBuss\Queue(name="test"),
 *     exchange=@EventBuss\Exchange(name="test"),
 *     bindingKeys={
 *         @EventBuss\BindingKey(
 *             name="test"
 *         )
 *     }
 * )
 *
 */
class Foo
{

}
