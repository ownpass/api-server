<?php
return [
    'OwnPassApplication\\V1\\Rest\\Account\\Controller' => [
        'description' => 'A REST service that manages OwnPass accounts.',
    ],
    'OwnPassApplication\\V1\\Rest\\Device\\Controller' => [
        'description' => 'A REST service that manages devices that have access to OwnPass.',
    ],
    'OwnPassApplication\\V1\\Rest\\User\\Controller' => [
        'description' => 'A REST service that retrieves information about the current authenticated user.',
    ],
    'OwnPassApplication\\V1\\Rpc\\DeviceActivate\\Controller' => [
        'description' => 'This RPC API can be used to activate a device.',
    ],
];
