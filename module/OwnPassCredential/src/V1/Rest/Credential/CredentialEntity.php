<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/api-server for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/api-server/master/LICENSE MIT
 */

namespace OwnPassCredential\V1\Rest\Credential;

use OwnPassCredential\Entity\Credential;
use OwnPassUser\V1\Rest\Account\AccountEntity;

class CredentialEntity
{
    public $id;
    public $account;
    public $credentials;
    public $title;
    public $description;
    public $urlRaw;

    public function __construct(Credential $credential)
    {
        $this->id = $credential->getId();
        $this->account = new AccountEntity($credential->getAccount());
        $this->credentials = $credential->getCredentials();
        $this->title = $credential->getTitle();
        $this->description = $credential->getDescription();
        $this->urlRaw = $credential->getUrlRaw();
    }
}
