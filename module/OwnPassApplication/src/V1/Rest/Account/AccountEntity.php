<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/api-server for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/api-server/master/LICENSE MIT
 */

namespace OwnPassApplication\V1\Rest\Account;

use OwnPassApplication\Entity\Account;
use RuntimeException;

class AccountEntity
{
    public $id;
    public $creation_date;
    public $update_date;
    public $status;
    public $name;
    public $role;
    public $email_address;

    public function __construct(Account $account)
    {
        $this->id = $account->getId();
        $this->creation_date = $account->getCreationDate();
        $this->update_date = $account->getUpdateDate();
        $this->role = $account->getRole();
        $this->name = $account->getName();
        $this->email_address = $account->getEmailAddress();

        switch ($account->getStatus()) {
            case Account::STATUS_ACTIVE:
                $this->status = 'active';
                break;

            case Account::STATUS_INACTIVE:
                $this->status = 'inactive';
                break;

            case Account::STATUS_INVITED:
                $this->status = 'invited';
                break;

            default:
                throw new RuntimeException(sprintf('The status "%s" is not implemented yet.', $account->getStatus()));
        }
    }
}
