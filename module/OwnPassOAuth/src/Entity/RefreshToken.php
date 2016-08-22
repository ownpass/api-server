<?php
/**
 * This file is part of Own Pass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/ownpass for the canonical source repository
 * @copyright Copyright (c) 2016-2017 Own Pass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/ownpass/master/LICENSE MIT
 */

namespace OwnPassOAuth\Entity;

use DateTimeInterface;
use OwnPassUser\Entity\Account;

class RefreshToken
{
    /**
     * @var string
     */
    private $refreshToken;

    /**
     * @var Application
     */
    private $application;

    /**
     * @var Account
     */
    private $account;

    /**
     * @var DateTimeInterface
     */
    private $expires;

    /**
     * @var string|null
     */
    private $scope;

    public function __construct(
        $refreshToken,
        Application $application,
        Account $account,
        DateTimeInterface $expires
    ) {
        $this->refreshToken = $refreshToken;
        $this->application = $application;
        $this->account = $account;
        $this->expires = $expires;
    }

    /**
     * @return string
     */
    public function getRefreshToken()
    {
        return $this->refreshToken;
    }

    /**
     * @return Application
     */
    public function getApplication()
    {
        return $this->application;
    }

    /**
     * @return Account
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * @return DateTimeInterface
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
     * @param string|null $scope
     */
    public function setScope($scope)
    {
        $this->scope = $scope;
    }
}
