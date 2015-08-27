<?php
namespace OldTown\EventBuss;

use OldTown\EventBuss\Factory\ServiceAbstractFactory;

return [
    'service_manager' => [
        'abstract_factories' =>[
            ServiceAbstractFactory::class => ServiceAbstractFactory::class
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
                'connection' => 'default'
            ]
        ]

    ]
];