<?php
namespace OldTown\EventBuss;

use OldTown\EventBuss\Driver\DriverChain;
use OldTown\EventBuss\Driver\RabbitMqDriver;
use OldTown\EventBuss\EventBussManager\EventBussManager;
use OldTown\EventBuss\EventBussManager\EventBussManagerAbstractFactory;
use OldTown\EventBuss\EventBussManager\EventBussManagerFactory;
use OldTown\EventBuss\EventBussManager\EventBussPluginManager;
use OldTown\EventBuss\EventBussManager\EventBussPluginManagerFactory;
use OldTown\EventBuss\Options\ModuleOptions;
use OldTown\EventBuss\Options\ModuleOptionsFactory;


return [
    'service_manager' => [
        'abstract_factories' =>[
            EventBussManagerAbstractFactory::class => EventBussManagerAbstractFactory::class
        ],
        'factories' => [
            ModuleOptions::class => ModuleOptionsFactory::class,
            EventBussPluginManager::class => EventBussPluginManagerFactory::class
        ],
        'aliases' => [
            'eventBussPluginManager' => EventBussPluginManager::class
        ]
    ],
    'event_buss_manager' => [
        'factories' => [
            EventBussManager::class => EventBussManagerFactory::class,
        ],
        'aliases' => [
            'default' => EventBussManager::class
        ]
    ],
    'event_buss' => [
        'connection' => [
            'default' => [
                'params' => [
                    'host'     => 'localhost',
                    'port'     => '5672',
                    'vhost'    => '/',
                    'login'    => 'guest',
                    'password' => 'guest'
                ]
            ]
        ],
        'event_buss_manager' => [
            'default' => [
                'driver' => 'default'
            ]
        ],
        'driver' => [
            'default' => [
                'class' => DriverChain::class,
                'drivers' => [
                    'amqp' => [
                        'class' => RabbitMqDriver::class,
                        'connection' => 'default'
                    ]
                ]
            ]
        ]

    ]
];