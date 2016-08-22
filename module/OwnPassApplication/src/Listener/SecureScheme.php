<?php
/**
 * This file is part of Own Pass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/ownpass for the canonical source repository
 * @copyright Copyright (c) 2016-2017 Own Pass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/ownpass/master/LICENSE MIT
 */

namespace OwnPassApplication\Listener;

use Zend\Console\Request as ConsoleRequest;
use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventManagerInterface;
use Zend\Http\PhpEnvironment\Response;
use Zend\Mvc\MvcEvent;
use Zend\Uri\Uri;

class SecureScheme extends AbstractListenerAggregate
{
    /**
     * @var bool
     */
    private $developmentMode;

    public function __construct($developmentMode)
    {
        $this->developmentMode = $developmentMode;
    }

    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $events->attach(MvcEvent::EVENT_DISPATCH, [$this, 'onDispatch'], -1);
    }

    public function onDispatch(MvcEvent $event)
    {
        if ($event->getRequest() instanceof ConsoleRequest) {
            return;
        }

        /** @var Uri $uri */
        $uri = $event->getRequest()->getUri();

        if ($uri->getScheme() === 'https' || $this->developmentMode) {
            return;
        }

        /** @var Response $response */
        $response = $event->getResponse();
        $response->setStatusCode(Response::STATUS_CODE_403);
        $response->setContent('Cannot access Own Pass over HTTP scheme, HTTPS is required.');

        // TODO: Render a nice template

        return $response;
    }
}
