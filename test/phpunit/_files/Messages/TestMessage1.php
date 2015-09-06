<?php
/**
 * @link    https://github.com/old-town/event-buss
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBuss\PhpUnit\TestData\Messages;

use OldTown\EventBuss\Driver\RabbitMqDriver\MetadataReader\Annotations as EventBuss;
use \OldTown\EventBuss\Message\MessageInterface;

/**
 * Class TestMessage1
 *
 * @package OldTown\EventBuss\TestData\Messages
 *
 * @EventBuss\EventBussMessage(
 *     queue=@EventBuss\Queue(name="test_queue_name_message1"),
 *     exchange=@EventBuss\Exchange(name="test_exchange_name_foo", type="topic"),
 *     bindingKeys={
 *         @EventBuss\BindingKey(
 *             name="*.procedure.*"
 *         ),
 *         @EventBuss\BindingKey(
 *             name="delete.procedure.*"
 *         )
 *     }
 * )
 *
 *
 */
class TestMessage1 implements MessageInterface
{

}
