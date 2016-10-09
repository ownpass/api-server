<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/ownpass for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/ownpass/master/LICENSE MIT
 */

namespace OwnPassOAuth\Storage;

use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use OAuth2\Storage\AccessTokenInterface;
use OAuth2\Storage\AuthorizationCodeInterface;
use OAuth2\Storage\ClientCredentialsInterface;
use OAuth2\Storage\RefreshTokenInterface;
use OAuth2\Storage\ScopeInterface;
use OAuth2\Storage\UserCredentialsInterface;
use OwnPassUser\Entity\Account;
use OwnPassOAuth\Entity\AccessToken;
use OwnPassOAuth\Entity\Application;
use OwnPassOAuth\Entity\AuthorizationCode;
use OwnPassOAuth\Entity\RefreshToken;
use OwnPassUser\Entity\Identity;
use Zend\Crypt\Password\PasswordInterface;

class Storage implements
    AccessTokenInterface,
    AuthorizationCodeInterface,
    ClientCredentialsInterface,
    RefreshTokenInterface,
    UserCredentialsInterface,
    ScopeInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var PasswordInterface
     */
    private $crypter;

    public function __construct(EntityManagerInterface $entityManager, PasswordInterface $crypter)
    {
        $this->entityManager = $entityManager;
        $this->crypter = $crypter;
    }

    /**
     * @param string $id
     * @return Application
     */
    public function getApplication($id)
    {
        try {
            $result = $this->entityManager->find(Application::class, $id);
        } catch (Exception $e) {
            $result = null;
        }

        return $result;
    }

    /**
     * @param string $id
     * @return Account
     */
    private function getAccount($id)
    {
        try {
            $result = $this->entityManager->find(Account::class, $id);
        } catch (Exception $e) {
            $result = null;
        }

        return $result;
    }

    public function getAccessToken($oauthToken)
    {
        /** @var AccessToken $accessToken */
        $accessToken = $this->entityManager->getRepository(AccessToken::class)->find($oauthToken);

        if (!$accessToken) {
            return null;
        }

        $account = $accessToken->getAccount();

        return [
            'client_id' => $accessToken->getApplication()->getClientId(),
            'user_id' => $account ? $account->getId()->toString() : null,
            'expires' => $accessToken->getExpires()->format('U'),
            'scope' => $accessToken->getScope(),
            'id_token' => null,
        ];
    }

    public function setAccessToken($oauthToken, $clientId, $userId, $expires, $scope = null)
    {
        $application = $this->getApplication($clientId);
        if (!$application) {
            return false;
        }

        /** @var Account $account */
        $account = $userId ? $this->getAccount($userId) : null;

        $expireDate = new DateTime();
        $expireDate->setTimestamp($expires);

        $accessToken = new AccessToken($oauthToken, $application, $expireDate);
        $accessToken->setAccount($account);
        $accessToken->setScope($scope);

        $this->entityManager->persist($accessToken);
        $this->entityManager->flush($accessToken);
    }

    public function getAuthorizationCode($code)
    {
        /** @var AuthorizationCode $authorizationCode */
        $authorizationCode = $this->entityManager->getRepository(AuthorizationCode::class)->find($code);

        if (!$authorizationCode) {
            return null;
        }

        $account = $authorizationCode->getAccount();

        return [
            'client_id' => $authorizationCode->getApplication()->getClientId(),
            'user_id' => $account ? $account->getId()->toString() : null,
            'expires' => (int)$authorizationCode->getExpires()->format('U'),
            'redirect_uri' => $authorizationCode->getRedirectUri(),
            'scope' => $authorizationCode->getScope(),
        ];
    }

    public function setAuthorizationCode($code, $client_id, $user_id, $redirect_uri, $expires, $scope = null)
    {
        $authorizationCode = $this->getAuthorizationCode($code);

        if ($authorizationCode) {
            return false;
        }

        $application = $this->getApplication($client_id);
        $account = $this->getAccount($user_id);

        $expireDate = new DateTime();
        $expireDate->setTimestamp($expires);

        $authorizationCode = new AuthorizationCode($code, $application, $redirect_uri, $expireDate, $account);
        $authorizationCode->setScope($scope);

        $this->entityManager->persist($authorizationCode);
        $this->entityManager->flush($authorizationCode);

        return true;
    }

    public function expireAuthorizationCode($code)
    {
        $qb = $this->entityManager->createQueryBuilder();
        $qb->delete();
        $qb->from(AuthorizationCode::class, 'a');
        $qb->where($qb->expr()->eq('a.authorizationCode', ':code'));
        $qb->setParameter(':code', $code);
        $qb->getQuery()->execute();
    }

    public function checkClientCredentials($clientId, $clientSecret = null)
    {
        $application = $this->getApplication($clientId);

        if (!$application) {
            return false;
        }

        return $this->crypter->verify($clientSecret, $application->getClientSecret());
    }

    public function isPublicClient($clientId)
    {
        /** @var Application $application */
        $application = $this->getApplication($clientId);

        if (!$application) {
            return false;
        }

        return empty($application->getClientSecret());
    }

    public function getClientDetails($clientId)
    {
        $application = $this->getApplication($clientId);

        if (!$application) {
            return false;
        }

        return [
            'client_id' => $application->getClientId(),
            'redirect_uri' => $application->getRedirectUri(),
            'grant_types' => null,
            'user_id' => null,
            'scope' => null,
        ];
    }

    public function getClientScope($client_id)
    {
        return '';
    }

    public function checkRestrictedGrantType($clientId, $grantType)
    {
        /** @var array $details */
        $details = $this->getClientDetails($clientId);

        if (isset($details['grant_types'])) {
            $grantTypes = explode(' ', $details['grant_types']);

            return in_array($grantType, (array)$grantTypes);
        }

        return true;
    }

    public function getRefreshToken($oauthToken)
    {
        /** @var RefreshToken $refreshToken */
        $refreshToken = $this->entityManager->getRepository(RefreshToken::class)->find($oauthToken);

        if (!$refreshToken) {
            return null;
        }

        return [
            'refresh_token' => null,
            'client_id' => $refreshToken->getApplication()->getClientId(),
            'user_id' => $refreshToken->getAccount()->getId()->toString(),
            'expires' => $refreshToken->getExpires()->format('U'),
            'scope' => $refreshToken->getScope(),
        ];
    }

    public function setRefreshToken($refreshToken, $clientId, $userId, $expires, $scope = null)
    {
        $application = $this->getApplication($clientId);
        if (!$application) {
            return;
        }

        /** @var Account $account */
        $account = $this->getAccount($userId);
        if (!$account) {
            return;
        }

        $expireDate = new DateTime();
        $expireDate->setTimestamp($expires);

        $refreshToken = new RefreshToken($refreshToken, $application, $account, $expireDate);
        $refreshToken->setScope($scope);

        $this->entityManager->persist($refreshToken);
        $this->entityManager->flush($refreshToken);
    }

    public function unsetRefreshToken($refreshToken)
    {
        $qb = $this->entityManager->createQueryBuilder();
        $qb->delete();
        $qb->from(RefreshToken::class, 't');
        $qb->where($qb->expr()->eq('t.refreshToken', ':token'));
        $qb->setParameter(':token', $refreshToken);
        $qb->getQuery()->execute();
    }

    public function checkUserCredentials($username, $password)
    {
        $identityRepository = $this->entityManager->getRepository(Identity::class);

        /** @var Identity $identity */
        $identity = $identityRepository->findOneBy([
            'directory' => 'username',
            'identity' => $username,
        ]);

        if (!$identity) {
            return false;
        }

        $account = $identity->getAccount();

        /** @var string $credential */
        $credential = $account->getCredential();

        return $this->crypter->verify($password, $credential);
    }

    public function getUserDetails($username)
    {
        $repository = $this->entityManager->getRepository(Identity::class);

        /** @var Identity $identity */
        $identity = $repository->findOneBy([
            'directory' => 'username',
            'identity' => $username,
        ]);

        if (!$identity) {
            return false;
        }

        $account = $identity->getAccount();

        if ($account->getStatus() !== Account::STATUS_ACTIVE) {
            return false;
        }

        return [
            'user_id' => $identity->getAccount()->getId()->toString(),
            'scope' => '',
        ];
    }

    public function scopeExists($scope)
    {
        // We don't support scopes yet.
        return false;
    }

    public function getDefaultScope($clientId = null)
    {
        return null;
    }
}
