<?php

return [
    'Admin' => [
        'can' => [
            'view_users', 'create_users', 'edit_users',
            'view_items', 'create_items', 'edit_items', 'delete_items',
            'view_transactions', 'create_transactions', 'delete_transaction',
        ],
    ],
    'User'  => [
        'can' => ['view_items', 'view_transactions', 'create_transactions', 'delete_transaction'],
    ],
];
