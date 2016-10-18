<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/ownpass for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/ownpass/master/LICENSE MIT
 */

namespace OwnPassApplication\Listener;

use Doctrine\ORM\EntityManagerInterface;
use OwnPassUser\Entity\Account;
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
    }

    private function guardRpcApi(MvcEvent $event, $method, $config)
    {
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

        if ($account->getRole() !== $guard[$method]) {
            return $this->buildError();
        }

        return null;
    }

    protected function buildError()
    {
        return new ApiProblemResponse(new ApiProblem(ApiProblemResponse::STATUS_CODE_403, 'Incorrect role.'));
    }
}
