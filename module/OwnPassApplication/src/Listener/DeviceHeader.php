<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/ownpass for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/ownpass/master/LICENSE MIT
 */

namespace OwnPassApplication\Listener;

use Doctrine\ORM\EntityManager;
use Exception;
use OwnPassApplication\Entity\Device;
use RuntimeException;
use Zend\Console\Request as ConsoleRequest;
use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventManagerInterface;
use Zend\Http\Header\GenericHeader;
use Zend\Mvc\MvcEvent;
use Zend\Router\RouteMatch;
use ZF\ApiProblem\ApiProblem;
use ZF\ApiProblem\ApiProblemResponse;

class DeviceHeader extends AbstractListenerAggregate
{
    const HEADER_NAME = 'X-OwnPass-Device';

    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $events->attach(MvcEvent::EVENT_ROUTE, [$this, 'onRoute'], -10);
    }

    public function onRoute(MvcEvent $event)
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

            if (!array_key_exists('entity_device_guard', $config)) {
                throw new RuntimeException(sprintf(
                    'Missing "entity_device_guard" configuration for API.',
                    $config['route_name']
                ));
            }

            $guard = $config['entity_device_guard'];
        } else {
            if (!in_array($method, $config['collection_http_methods'])) {
                return null;
            }

            if (!array_key_exists('collection_device_guard', $config)) {
                throw new RuntimeException(sprintf(
                    'Missing "collection_device_guard" configuration for API.',
                    $config['route_name']
                ));
            }

            $guard = $config['collection_device_guard'];
        }

        return $this->validateGuard($event, $method, $guard);
    }

    private function guardRpcApi(MvcEvent $event, $method, $config)
    {
        if (!in_array($method, $config['http_methods'])) {
            return null;
        }

        if (substr($config['route_name'], 0, 13) === 'zf-apigility/') {
            return;
        }

        if (!array_key_exists('device_guard', $config)) {
            throw new RuntimeException(sprintf(
                'Missing "device_guard" configuration for route "%s".',
                $config['route_name']
            ));
        }

        return $this->validateGuard($event, $method, $config['device_guard']);
    }

    private function validateGuard(MvcEvent $event, $method, array $guard)
    {
        if (!array_key_exists($method, $guard)) {
            throw new RuntimeException('No guard set for method ' . $method);
        }

        if (!$guard[$method]) {
            return null;
        }

        $response = $this->validateHeader($event);
        if (!$response) {
            return null;
        }

        return $response;
    }

    public function validateHeader(MvcEvent $event)
    {
        /** @var GenericHeader $header */
        $header = $event->getRequest()->getHeaders()->get(self::HEADER_NAME);

        if (!$header) {
            return $this->buildErrorResponse($event, sprintf(
                'Missing "%s" header.',
                self::HEADER_NAME
            ));
        }

        $device = $this->getDevice($event, $header->getFieldValue());
        if (!$device) {
            return $this->buildErrorResponse($event, sprintf(
                'Invalid device id provided in header "%s".',
                self::HEADER_NAME
            ));
        }

        if ($device->getActivationCode()) {
            return $this->buildErrorResponse($event, sprintf(
                'The device with id "%s" is not activated.',
                $device->getId()
            ));
        }

        return null;
    }

    private function getDevice(MvcEvent $event, $deviceId)
    {
        try {
            $entityManager = $event->getApplication()->getServiceManager()->get(EntityManager::class);

            /** @var Device $device */
            $device = $entityManager->find(Device::class, $deviceId);
        } catch (Exception $e) {
            $device = null;
        }

        return $device;
    }

    private function buildErrorResponse(MvcEvent $event, $msg)
    {
        $response = new ApiProblemResponse(new ApiProblem(ApiProblemResponse::STATUS_CODE_403, $msg));

        $event->setResponse($response);

        return $response;
    }
}
