<?php
/**
 * @link https://github.com/old-town/event-buss
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBuss\Driver;

use Zend\EventManager\EventInterface;
use Zend\EventManager\ResponseCollection;

/**
 * Interface EventBussDriverInterface
 *
 * @package OldTown\EventBuss\Driver
 */
interface EventBussDriverInterface
{
    /**
     * Trigger an event
     *
     * Should allow handling the following scenarios:
     * - Passing Event object only
     * - Passing event name and Event object only
     * - Passing event name, target, and Event object
     * - Passing event name, target, and array|ArrayAccess of arguments
     * - Passing event name, target, array|ArrayAccess of arguments, and callback
     *
     * @param  string|EventInterface $event
     * @param  object|string $target
     * @param  array|object $argv
     * @param  null|callable $callback
     * @return ResponseCollection
     */
    public function trigger($event, $target = null, $argv = [], $callback = null);

    /**
     * Опции переданные при создание драйвера
     *
     * @return array
     */
    public function getDriverOptions();

    /**
     * Устанавливает опции
     *
     * @param array $options
     *
     * @return $this
     */
    public function setOptions(array $options = []);
}
