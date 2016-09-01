<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/ownpass for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/ownpass/master/LICENSE MIT
 */

namespace OwnPassApplication\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use ZF\Apigility\Admin\Module as AdminModule;

class Api extends AbstractActionController
{
    public function indexAction()
    {
        if (class_exists(AdminModule::class, false)) {
            return $this->redirect()->toRoute('zf-apigility/ui');
        }

        return new JsonModel();
    }
}
