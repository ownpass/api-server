<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/api-server for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/api-server/master/LICENSE MIT
 */

namespace OwnPassApplication\Controller;

use OwnPassApplication\TaskService\KeyManager as KeyManagerTaskService;
use Zend\Mvc\Console\Controller\AbstractConsoleController;

class KeyManagerCli extends AbstractConsoleController
{
    /**
     * @var KeyManagerTaskService
     */
    private $keyManager;

    public function __construct(KeyManagerTaskService $keyManager)
    {
        $this->keyManager = $keyManager;
    }

    public function generateAction()
    {
        $this->keyManager->generate();
    }

    public function removeAction()
    {
        $this->keyManager->remove();
    }
}
