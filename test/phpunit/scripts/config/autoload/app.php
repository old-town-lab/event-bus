<?php

require_once __DIR__ . '/../../Handler.php';

return [
    'console' => [
        'router' => [
            'routes' => [
                'attach' => [
                    'options' => [
                        'route'    => 'attach  [--host=] [--port=] [--vhost=] [--login=] [--password=]',
                        'defaults' => [
                            'controller' => Handler::class,
                            'action'     => 'attach'
                        ]
                    ],
                ],
                'trigger' => [
                    'options' => [
                        'route'    => 'trigger  [--host=] [--port=] [--vhost=] [--login=] [--password=]',
                        'defaults' => [
                            'controller' => Handler::class,
                            'action'     => 'trigger'
                        ]
                    ],
                ]
            ]
        ]
    ],
    'controllers' => [
        'invokables' => [
            Handler::class => Handler::class
        ]
    ],
    'event_bus' => [
        'connection' => [
            'rabbitMqDriver_amqpPhpExtensionAdapter' => [
                'params' => [
                ]
            ]
        ],
        'event_bus_manager' => [
            'rabbitMqDriver_amqpPhpExtensionAdapter_attach' => [
                'driver' => 'rabbitMqDriver_amqpPhpExtensionAdapter_attach'
            ],
            'rabbitMqDriver_amqpPhpExtensionAdapter_trigger' => [
                'driver' => 'rabbitMqDriver_amqpPhpExtensionAdapter_trigger'
            ]
        ],
        'driver' => [
            'rabbitMqDriver_amqpPhpExtensionAdapter_attach' => [
                'pluginName' => OldTown\EventBus\Driver\RabbitMqDriver::class,
                'connection' => 'rabbitMqDriver_amqpPhpExtensionAdapter',
                'paths' => [
                    __DIR__ . '/../../../_files/TestAttachTriggerMessage/'
                ]
            ],
            'rabbitMqDriver_amqpPhpExtensionAdapter_trigger' => [
                'pluginName' => OldTown\EventBus\Driver\RabbitMqDriver::class,
                'connection' => 'rabbitMqDriver_amqpPhpExtensionAdapter',
                'paths' => [
                    __DIR__ . '/../../../_files/TestAttachTriggerMessage/'
                ]
            ]
        ]

    ]
];
