<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/api-server for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/api-server/master/LICENSE MIT
 */

namespace OwnPassApplication\Listener;

use Doctrine\ORM\EntityManagerInterface;
use OwnPassApplication\Entity\Account;
use RuntimeException;
use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventManagerInterface;
use Zend\Mvc\MvcEvent;
use Zend\Router\RouteMatch;
use ZF\ApiProblem\ApiProblem;
use ZF\ApiProblem\ApiProblemResponse;
use ZF\MvcAuth\Identity\AuthenticatedIdentity;

class ValidateRole extends AbstractListenerAggregate
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var array
     */
    private $config;

    public function __construct(EntityManagerInterface $entityManager, array $config)
    {
        $this->entityManager = $entityManager;
        $this->config = $config;
    }

    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $events->attach(MvcEvent::EVENT_DISPATCH, [$this, 'onDispatch'], 2);
    }

    public function onDispatch(MvcEvent $event)
    {
        $config = $event->getApplication()->getServiceManager()->get('config');
        $routeMatch = $event->getRouteMatch();
        $controllerFqcn = $routeMatch->getParam('controller');

        if (array_key_exists($controllerFqcn, $config['zf-rest'])) {
            $method = $event->getRequest()->getMethod();

            return $this->guardRestApi($event, $method, $routeMatch, $config['zf-rest'][$controllerFqcn]);
        }

        if (array_key_exists($controllerFqcn, $config['zf-rpc'])) {
            $method = $event->getRequest()->getMethod();

            return $this->guardRpcApi($event, $method, $config['zf-rpc'][$controllerFqcn]);
        }
    }

    private function guardRestApi(MvcEvent $event, $method, RouteMatch $routeMatch, $config)
    {
        if (substr($config['route_name'], 0, 13) === 'zf-apigility/') {
            return;
        }

        $isEntity = $routeMatch->getParam($config['route_identifier_name']) !== null;

        if ($isEntity) {
            if (!in_array($method, $config['entity_http_methods'])) {
                return null;
            }

            if (!array_key_exists('entity_role_guard', $config)) {
                throw new RuntimeException('Missing "entity_role_guard" configuration for API.');
            }

            $guard = $config['entity_role_guard'];
        } else {
            if (!in_array($method, $config['collection_http_methods'])) {
                return null;
            }

            if (!array_key_exists('collection_role_guard', $config)) {
                throw new RuntimeException('Missing "collection_role_guard" configuration for API.');
            }

            $guard = $config['collection_role_guard'];
        }

        return $this->validateGuard($event, $method, $guard);
    }

    private function guardRpcApi(MvcEvent $event, $method, $config)
    {
        if (substr($config['route_name'], 0, 13) === 'zf-apigility/') {
            return;
        }

        if (!in_array($method, $config['http_methods'])) {
            return null;
        }

        if (!array_key_exists('role_guard', $config)) {
            return null;
        }

        return $this->validateGuard($event, $method, $config['role_guard']);
    }

    private function validateGuard($event, $method, $guard)
    {
        if (!array_key_exists($method, $guard) || $guard[$method] === null) {
            return null;
        }

        /** @var AuthenticatedIdentity $authenticatedIdentity    */
        $authenticatedIdentity = $event->getParam('ZF\MvcAuth\Identity');

        if (!$authenticatedIdentity) {
            return $this->buildError();
        }

        /** @var array $identity */
        $identity = $authenticatedIdentity->getAuthenticationIdentity();

        /** @var Account $account */
        $account = $this->entityManager->find(Account::class, $identity['user_id']);

        if ($guard[$method] === null) {
            return null;
        } elseif (is_array($guard[$method]) && in_array($account->getRole(), $guard[$method])) {
            return null;
        } elseif ($account->getRole() === $guard[$method]) {
            return null;
        }

        return $this->buildError();
    }

    protected function buildError()
    {
        return new ApiProblemResponse(new ApiProblem(ApiProblemResponse::STATUS_CODE_403, 'Incorrect role.'));
    }
}
