<?php

return [
    'modules' => [
        'OldTown\\EventBuss'
    ],
    'module_listener_options' => [
        'module_paths' => [
            'OldTown\\EventBuss' => __DIR__ . '/../../'
        ]
    ]
];