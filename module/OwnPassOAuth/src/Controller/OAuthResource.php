<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/ownpass for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/ownpass/master/LICENSE MIT
 */

namespace OwnPassOAuth\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;

class OAuthResource extends AbstractActionController
{
    public function indexAction()
    {
        return new JsonModel([
            'success' => true,
        ]);
    }
}
