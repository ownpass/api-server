<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/api-server for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/api-server/master/LICENSE MIT
 */

return [
    'doctrine' => [
        'connection' => [
            'orm_default' => [
                'driverClass' => 'Doctrine\\DBAL\\Driver\\PDOMySql\\Driver',
                'params' => [
                    'host' => 'localhost',
                    'port' => '3306',
                    'user' => '',
                    'password' => '',
                    'dbname' => 'ownpass',
                ],
            ],
        ],
        'configuration' => [
            'orm_default' => [
                'naming_strategy' => 'UnderscoreNamingStrategy',
                'proxy_dir' => 'data/doctrine/orm/proxy',
                'proxy_namespace' => 'OwnPassDoctrine\\Proxy',
                'types' => [
                    'uuid_binary' => 'Ramsey\\Uuid\\Doctrine\\UuidBinaryType',
                ],
            ],
        ],
    ],
];
