<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/api-server for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/api-server/master/LICENSE MIT
 */

return [
    'view_manager' => [
        'display_exceptions' => false,
        'display_not_found_reason' => false,
        'strategies' => [
            'ViewJsonStrategy',
        ],
    ],
];
