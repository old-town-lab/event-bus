<?php
/**
 * @link https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\Message;

use Zend\InputFilter\InputFilterPluginManager;

/**
 * Interface InputFilterPluginManagerAwareInterface
 *
 * @package OldTown\EventBus\Message
 */
interface InputFilterPluginManagerAwareInterface
{
    /**
     * Set input filter
     *
     * @param  InputFilterPluginManager $inputFilterPluginManager
     * @return $this
     */
    public function setInputFilterPluginManager(InputFilterPluginManager $inputFilterPluginManager);

    /**
     * Retrieve input filter
     *
     * @return InputFilterPluginManager
     */
    public function getInputFilterPluginManager();
}
