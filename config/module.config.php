<?php
namespace OldTown\EventBus;

use OldTown\EventBus\Driver\DriverChain;
use OldTown\EventBus\Driver\DriverChainFactory;
use OldTown\EventBus\Driver\EventBussDriverAbstractFactory;
use OldTown\EventBus\Driver\EventBussDriverPluginManager;
use OldTown\EventBus\Driver\EventBussDriverPluginManagerFactory;
use OldTown\EventBus\Driver\RabbitMqDriver;
use OldTown\EventBus\EventBussManager\EventBussManagerFacade;
use OldTown\EventBus\EventBussManager\EventBussManagerAbstractFactory;
use OldTown\EventBus\EventBussManager\EventBussManagerFactory;
use OldTown\EventBus\EventBussManager\EventBussPluginManager;
use OldTown\EventBus\EventBussManager\EventBussPluginManagerFactory;
use OldTown\EventBus\MetadataReader\EventBussMetadataReaderPluginManager;
use OldTown\EventBus\MetadataReader\EventBussMetadataReaderPluginManagerFactory;
use OldTown\EventBus\Options\ModuleOptions;
use OldTown\EventBus\Options\ModuleOptionsFactory;
use OldTown\EventBus\Driver\EventBussPluginDriverAbstractFactory;
use OldTown\EventBus\Driver\RabbitMqDriver\MetadataReader\AnnotationReader;

return [
    'service_manager' => [
        'abstract_factories' =>[
            EventBussManagerAbstractFactory::class => EventBussManagerAbstractFactory::class,
            EventBussDriverAbstractFactory::class => EventBussDriverAbstractFactory::class
        ],
        'factories' => [
            ModuleOptions::class => ModuleOptionsFactory::class,
            EventBussPluginManager::class => EventBussPluginManagerFactory::class,
            EventBussDriverPluginManager::class => EventBussDriverPluginManagerFactory::class,
            EventBussMetadataReaderPluginManager::class => EventBussMetadataReaderPluginManagerFactory::class
        ],
        'aliases' => [
            'eventBussPluginManager' => EventBussPluginManager::class,
            'eventBussDriverManager' => EventBussDriverPluginManager::class,
            'eventBussMetadataReaderManager' => EventBussMetadataReaderPluginManager::class
        ]
    ],
    'event_buss_manager' => [
        'factories' => [
            EventBussManagerFacade::class => EventBussManagerFactory::class,
        ],
        'aliases' => [
            'default' => EventBussManagerFacade::class
        ]
    ],
    'event_buss_driver' => [
        'factories' => [
            DriverChain::class => DriverChainFactory::class,
        ],
        'abstract_factories' =>[
            EventBussPluginDriverAbstractFactory::class => EventBussPluginDriverAbstractFactory::class
        ],
        'aliases' => [
            'chain' => DriverChain::class,
            'rabbit' => RabbitMqDriver::class
        ]
    ],
    'event_buss_metadata_reader' => [
        'invokables' => [
            AnnotationReader::class => AnnotationReader::class
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