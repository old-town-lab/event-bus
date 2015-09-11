<?php
/**
 * @link https://github.com/old-town/event-bus
 * @author  Malofeykin Andrey  <and-rey2@yandex.ru>
 */
namespace OldTown\EventBus\Message;

use Zend\InputFilter\InputFilterPluginManager;

/**
 * Class InputFilterPluginManagerAwareTrait
 *
 * @package OldTown\EventBus\Message
 */
trait InputFilterPluginManagerAwareTrait
{
    /**
     * @var InputFilterPluginManager|null
     */
    protected $inputFilterPluginManager;

    /**
     * Set input filter plugin manager
     *
     * @param  InputFilterPluginManager $inputFilterPluginManager
     * @return $this
     */
    public function setInputFilterPluginManager(InputFilterPluginManager $inputFilterPluginManager)
    {
        $this->inputFilterPluginManager = $inputFilterPluginManager;

        return $this;
    }

    /**
     * Retrieve input filter plugin manager
     *
     * @return InputFilterPluginManager
     */
    public function getInputFilterPluginManager()
    {
        return $this->inputFilterPluginManager;
    }
}
