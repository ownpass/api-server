<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/api-server for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/api-server/master/LICENSE MIT
 */

namespace OwnPassCredential\V1\Rpc\GeneratePassword;

use OwnPassCredential\TaskService\Generator;

class GeneratePasswordControllerFactory
{
    public function __invoke($controllers)
    {
        $taskService = $controllers->get(Generator::class);

        return new GeneratePasswordController($taskService);
    }
}
