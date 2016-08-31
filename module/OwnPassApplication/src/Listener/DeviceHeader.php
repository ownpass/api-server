<?php
/**
 * This file is part of Own Pass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/ownpass for the canonical source repository
 * @copyright Copyright (c) 2016-2017 Own Pass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/ownpass/master/LICENSE MIT
 */

namespace OwnPassApplication\Listener;

use Doctrine\ORM\EntityManager;
use Exception;
use OwnPassApplication\Entity\Device;
use Zend\Console\Request as ConsoleRequest;
use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventManagerInterface;
use Zend\Http\Header\GenericHeader;
use Zend\Mvc\MvcEvent;
use ZF\ApiProblem\ApiProblem;
use ZF\ApiProblem\ApiProblemResponse;

class DeviceHeader extends AbstractListenerAggregate
{
    const HEADER_NAME = 'X-OwnPass-Device';

    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $events->attach(MvcEvent::EVENT_DISPATCH, [$this, 'onDispatch'], -1);
    }

    public function onDispatch(MvcEvent $event)
    {
        if ($event->getRequest() instanceof ConsoleRequest) {
            return;
        }

        $config = $event->getApplication()->getServiceManager()->get('config');
        $routeName = $event->getRouteMatch()->getMatchedRouteName();

        if (!array_key_exists($routeName, $config['router']['routes'])) {
            return;
        }

        $route = $config['router']['routes'][$routeName];
        if (!array_key_exists('device_required', $route['options']) || !$route['options']['device_required']) {
            return;
        }

        /** @var GenericHeader $header */
        $header = $event->getRequest()->getHeaders()->get(self::HEADER_NAME);
        if (!$header) {
            $this->buildErrorResponse($event, sprintf(
                'Missing "%s" header.',
                self::HEADER_NAME
            ));

            return $event->getResponse();
        }

        $deviceId = $header->getFieldValue();

        try {
            $entityManager = $event->getApplication()->getServiceManager()->get(EntityManager::class);

            /** @var Device $device */
            $device = $entityManager->find(Device::class, $deviceId);
        } catch (Exception $e) {
            $this->buildErrorResponse($event, sprintf(
                'Invalid device id provided in header "%s".',
                self::HEADER_NAME
            ));

            return $event->getResponse();
        }

        if ($device->getActivationCode()) {
            $this->buildErrorResponse($event, sprintf(
                'The device with id "%s" is not activated.',
                $deviceId
            ));

            return $event->getResponse();
        }
    }

    private function buildErrorResponse(MvcEvent $event, $msg)
    {
        $response = new ApiProblemResponse(new ApiProblem(ApiProblemResponse::STATUS_CODE_403, $msg));

        $event->setResponse($response);
    }
}
