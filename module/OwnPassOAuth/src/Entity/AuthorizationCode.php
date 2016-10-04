<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/ownpass for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/ownpass/master/LICENSE MIT
 */

namespace OwnPassOAuth\Entity;

use DateTimeInterface;
use OwnPassUser\Entity\Account;

class AuthorizationCode
{
    /**
     * @var string
     */
    private $authorizationCode;

    /**
     * @var Application
     */
    private $application;

    /**
     * @var string
     */
    private $redirectUri;

    /**
     * @var DateTimeInterface
     */
    private $expires;

    /**
     * @var string
     */
    private $scope;

    /**
     * @var Account|null
     */
    private $account;

    /**
     * Initializes a new instance of this class.
     *
     * @param string $authorizationCode
     * @param Application $application
     * @param Account $account
     * @param string $redirectUri
     * @param DateTimeInterface $expires
     */
    public function __construct(
        $authorizationCode,
        Application $application,
        $redirectUri,
        DateTimeInterface $expires,
        Account $account = null
    ) {
        $this->authorizationCode = $authorizationCode;
        $this->application = $application;
        $this->redirectUri = $redirectUri;
        $this->expires = $expires;
        $this->account = $account;
    }

    /**
     * @return string
     */
    public function getAuthorizationCode()
    {
        return $this->authorizationCode;
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
     * @return string
     */
    public function getRedirectUri()
    {
        return $this->redirectUri;
    }

    /**
     * @return DateTimeInterface
     */
    public function getExpires()
    {
        return $this->expires;
    }

    /**
     * @return string
     */
    public function getScope()
    {
        return $this->scope;
    }

    /**
     * @param string $scope
     */
    public function setScope($scope)
    {
        $this->scope = $scope;
    }
}
