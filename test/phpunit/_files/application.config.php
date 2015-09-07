<?php

return [
    'modules' => [
        'OldTown\\EventBus'
    ],
    'module_listener_options' => [
        'module_paths' => [
            'OldTown\\EventBus' => __DIR__ . '/../../'
        ]
    ]
];