<?php

/**
 * Class Handler
 */
class Handler extends \Zend\Mvc\Controller\AbstractConsoleController
{
    /**
     * @var array
     */
    protected $managers = [];

    /**
     *
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     * @throws \OldTown\EventBus\Driver\Exception\InvalidEventBusDriverConfigException
     * @throws \OldTown\EventBus\Driver\Exception\InvalidAdapterNameException
     */
    public function attachAction()
    {
        $manager = $this->getManager('event_bus.manager.rabbitMqDriver_amqpPhpExtensionAdapter_attach');
        $manager->initEventBus();

        $manager->attach(\OldTown\EventBus\PhpUnit\TestData\TestAttachTriggerMessage\Foo::class, function (\OldTown\EventBus\Message\MessageInterface $message) {
            echo $message->getContent() . "\n";
        });
    }

    /**
     *
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     * @throws \OldTown\EventBus\Driver\Exception\InvalidEventBusDriverConfigException
     * @throws \OldTown\EventBus\Driver\Exception\InvalidAdapterNameException
     */
    public function triggerAction()
    {
        $manager = $this->getManager('event_bus.manager.rabbitMqDriver_amqpPhpExtensionAdapter_trigger');

        $manager->initEventBus();

        /** @var \OldTown\EventBus\Message\EventBusMessagePluginManager $messagePluginManager */
        $messagePluginManager = $this->getServiceLocator()->get('eventBusMessageManager');

        /** @var \OldTown\EventBus\PhpUnit\TestData\TestAttachTriggerMessage\Foo $message */
        $message = $messagePluginManager->get(\OldTown\EventBus\PhpUnit\TestData\TestAttachTriggerMessage\Foo::class);

        $message->setTestProperty1('test_property_1');
        $message->setTestProperty2(true);
        $message->setTestProperty3([
            'key_1' => 'value_1',
            'key_2' => 'value_2',
            'key_3' => 'value_3'
        ]);

        $manager->trigger('abrakadabra', $message);
    }

    /**
     * @param $managerName
     *
     * @return OldTown\EventBus\EventBusManager\EventBusManagerFacade
     */
    public function getManager($managerName)
    {
        if (array_key_exists($managerName, $this->managers)) {
            return $this->managers[$managerName];
        }
        /** @var OldTown\EventBus\EventBusManager\EventBusManagerFacade $manager */
        $manager = $this->getServiceLocator()->get($managerName);

        /** @var \OldTown\EventBus\Driver\RabbitMqDriver $driver */
        $driver = $manager->getDriver();


        /** @var OldTown\EventBus\Driver\RabbitMqDriver\Adapter\AmqpPhpExtension $adapter */
        $adapter = $driver->getAdapter();
        $connectionConfig = $adapter->getConnectionConfig();
        /** @var Zend\Console\Request $request */
        $request = $this->getRequest();

        $connectionConfig['params'] = [
            'host'     => $request->getParam('host', 'localhost'),
            'port'     => $request->getParam('port', '5672'),
            'vhost'    => $request->getParam('vhost', '/'),
            'login'    => $request->getParam('login', 'guest'),
            'password' => $request->getParam('password', 'guest'),
        ];
        $adapter->setConnectionConfig($connectionConfig);

        $this->managers[$managerName] = $manager;
        return $this->managers[$managerName];
    }
}
