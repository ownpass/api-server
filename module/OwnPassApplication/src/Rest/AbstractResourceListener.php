<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/ownpass for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/ownpass/master/LICENSE MIT
 */

namespace OwnPassApplication\Rest;

use Doctrine\ORM\EntityManagerInterface;
use Exception;
use OwnPassUser\Entity\Account;
use ZF\Rest\AbstractResourceListener as BaseAbstractResourceListener;

abstract class AbstractResourceListener extends BaseAbstractResourceListener
{
    protected function getAccountId()
    {
        if (!$this->getIdentity()) {
            return null;
        }

        $identity = $this->getIdentity()->getAuthenticationIdentity();

        return $identity['user_id'];
    }

    public function getAccount(EntityManagerInterface $entityManager, $id)
    {
        try {
            $account = $entityManager->find(Account::class, $id);
        } catch (Exception $e) {
            $account = null;
        }

        return $account;
    }
}
