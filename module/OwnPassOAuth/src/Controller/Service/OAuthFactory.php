<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/ownpass for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/ownpass/master/LICENSE MIT
 */

namespace OwnPassOAuth\Controller\Service;

use DateInterval;
use Interop\Container\ContainerInterface;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Grant\PasswordGrant;
use OwnPassOAuth\Controller\OAuthToken;
use RuntimeException;
use Zend\ServiceManager\Factory\FactoryInterface;

class OAuthFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $server = $this->createServer();

        switch ($requestedName) {
            case OAuthToken::class:
                $controller = new OAuthToken($server);
                break;

            default:
                throw new RuntimeException('Invalid name requested: ' . $requestedName);
        }

        return $controller;
    }

    private function createServer()
    {
        $storage = null;

        $privateKey = 'file://path/to/private.key';
        $publicKey = 'file://path/to/public.key';

        $passwordGrant = new PasswordGrant($storage, $storage);
        $passwordGrant->setRefreshTokenTTL(new DateInterval('P1M'));

        $server = new AuthorizationServer($storage, $storage, $storage, $privateKey, $publicKey);
        $server->enableGrantType($passwordGrant, new DateInterval('PT1H'));

        return $server;
    }
}
