<?php
namespace OldTown\EventBuss;

use OldTown\EventBuss\Driver\DriverChain;
use OldTown\EventBuss\Driver\DriverChainFactory;
use OldTown\EventBuss\Driver\EventBussDriverAbstractFactory;
use OldTown\EventBuss\Driver\EventBussDriverPluginManager;
use OldTown\EventBuss\Driver\EventBussDriverPluginManagerFactory;
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
            EventBussManagerAbstractFactory::class => EventBussManagerAbstractFactory::class,
            EventBussDriverAbstractFactory::class => EventBussDriverAbstractFactory::class
        ],
        'factories' => [
            ModuleOptions::class => ModuleOptionsFactory::class,
            EventBussPluginManager::class => EventBussPluginManagerFactory::class,
            EventBussDriverPluginManager::class => EventBussDriverPluginManagerFactory::class
        ],
        'aliases' => [
            'eventBussPluginManager' => EventBussPluginManager::class,
            'eventBussDriverManager' => EventBussDriverPluginManager::class
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
    'event_buss_driver' => [
        'factories' => [
            DriverChain::class => DriverChainFactory::class,
        ],
        'aliases' => [
            'chain' => DriverChain::class
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
                'pluginName' => DriverChain::class,
                'drivers' => [
                    'amqp' => [
                        'pluginName' => RabbitMqDriver::class,
                        'connection' => 'default'
                    ]
                ]
            ]
        ]

    ]
];