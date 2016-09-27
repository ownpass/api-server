<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/ownpass for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/ownpass/master/LICENSE MIT
 */

namespace OwnPassUser\V1\Rest\Account;

use OwnPassUser\Entity\Account;

class AccountEntity
{
    public $id;
    public $creationDate;
    public $updateDate;
    public $name;
    public $role;
    public $email_address;

    public function __construct(Account $account)
    {
        $this->id = $account->getId();
        $this->creationDate = $account->getCreationDate();
        $this->updateDate = $account->getUpdateDate();
        $this->role = $account->getRole();
        $this->name = $account->getName();
        $this->email_address = $account->getEmailAddress();
    }
}
