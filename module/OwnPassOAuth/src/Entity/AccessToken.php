<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/ownpass for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/ownpass/master/LICENSE MIT
 */

namespace OwnPassOAuth\Entity;

use DateTime;
use DateTimeInterface;
use OwnPassUser\Entity\Account;

class AccessToken
{
    /**
     * @var string
     */
    private $accessToken;

    /**
     * @var Application
     */
    private $application;

    /**
     * @var Account|null
     */
    private $account;

    /**
     * @var DateTime
     */
    private $expires;

    /**
     * @var string|null
     */
    private $scope;

    public function __construct($accessToken, Application $application, DateTimeInterface $expires)
    {
        $this->accessToken = $accessToken;
        $this->application = $application;
        $this->expires = $expires;
    }

    /**
     * @return string
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * @return Application
     */
    public function getApplication()
    {
        return $this->application;
    }

    /**
     * @return Account|null
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * Sets the account.
     *
     * @param Account|null $account
     */
    public function setAccount(Account $account = null)
    {
        $this->account = $account;
    }

    /**
     * @return DateTime
     */
    public function getExpires()
    {
        return $this->expires;
    }

    /**
     * @return string|null
     */
    public function getScope()
    {
        return $this->scope;
    }

    /**
     * @param null|string $scope
     */
    public function setScope($scope)
    {
        $this->scope = $scope;
    }
}
