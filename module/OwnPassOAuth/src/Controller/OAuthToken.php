<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/ownpass for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/ownpass/master/LICENSE MIT
 */

namespace OwnPassOAuth\Controller;

use League\OAuth2\Server\AuthorizationServer;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;

class OAuthToken extends AbstractActionController
{
    private $server;

    public function __construct(AuthorizationServer $server)
    {
        $this->server = $server;
    }

    public function indexAction()
    {
        return new JsonModel([
            'success' => true,
        ]);
    }
}
