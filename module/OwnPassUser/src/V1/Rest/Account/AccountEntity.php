<?php
/**
 * This file is part of Own Pass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/ownpass for the canonical source repository
 * @copyright Copyright (c) 2016-2017 Own Pass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/ownpass/master/LICENSE MIT
 */

namespace OwnPassUser\V1\Rest\Account;

use OwnPassUser\Entity\Account;

class AccountEntity
{
    public $id;
    public $identity;
    public $firstName;
    public $lastName;

    public function __construct(Account $account)
    {
        $this->id = $account->getId();
        $this->identity = $account->getIdentity();
        $this->firstName = $account->getFirstName();
        $this->lastName = $account->getLastName();
    }
}
