<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/api-server for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/api-server/master/LICENSE MIT
 */

namespace OwnPassUser\Controller\Service;

use Interop\Container\ContainerInterface;
use OwnPassUser\Controller\Authenticate;
use OwnPassUser\Form\Login;
use Zend\Authentication\AuthenticationServiceInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class AuthenticateFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $authenticationService = $container->get(AuthenticationServiceInterface::class);
        $session = $container->get('AuthenticateSession');
        $loginForm = $container->get(Login::class);

        return new Authenticate($authenticationService, $session, $loginForm);
    }
}
