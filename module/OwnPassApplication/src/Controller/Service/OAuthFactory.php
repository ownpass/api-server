<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/api-server for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/api-server/master/LICENSE MIT
 */

namespace OwnPassApplication\Controller\Service;

use Interop\Container\ContainerInterface;
use OwnPassApplication\Controller\OAuth;
use OwnPassApplication\TaskService\OAuth as OAuthTaskService;
use Zend\Authentication\AuthenticationServiceInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class OAuthFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $oauthTaskService = $container->get(OAuthTaskService::class);

        $authService = $container->get(AuthenticationServiceInterface::class);

        return new OAuth($oauthTaskService, $authService);
    }
}
