<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/ownpass for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/ownpass/master/LICENSE MIT
 */

namespace OwnPassApplicationTest\Listener;

use OwnPassApplication\Listener\DeviceHeader;
use OwnPassApplication\Listener\SecureScheme;
use PHPUnit_Framework_TestCase;
use Zend\Console\Request;
use Zend\EventManager\EventManagerInterface;
use Zend\Http\Request as HttpRequest;
use Zend\Http\Response;
use Zend\Mvc\MvcEvent;
use Zend\Uri\Uri;

class SecureSchemeTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers OwnPassApplication\Listener\SecureScheme::__construct
     * @covers OwnPassApplication\Listener\SecureScheme::attach
     */
    public function testAttach()
    {
        // Arrange
        $listener = new SecureScheme(true);

        $eventsMockBuilder = $this->getMockBuilder(EventManagerInterface::class);
        $events = $eventsMockBuilder->getMockForAbstractClass();
        $events->expects($this->once())->method('attach')->with(
            $this->equalTo(MvcEvent::EVENT_DISPATCH),
            $this->equalTo([$listener, 'onDispatch']),
            $this->equalTo(-1)
        );

        // Act
        $listener->attach($events);

        // Assert
        // ...
    }

    /**
     * @covers OwnPassApplication\Listener\SecureScheme::__construct
     * @covers OwnPassApplication\Listener\SecureScheme::onDispatch
     */
    public function testOnDispatchWithConsoleRequest()
    {
        // Arrange
        $listener = new SecureScheme(true);

        $eventBuilder = $this->getMockBuilder(MvcEvent::class);

        $event = $eventBuilder->getMock();
        $event->expects($this->once())->method('getRequest')->willReturn(new Request());
        $event->expects($this->never())->method('getResponse');

        // Act
        $listener->onDispatch($event);

        // Assert
        // ...
    }

    /**
     * @covers OwnPassApplication\Listener\SecureScheme::__construct
     * @covers OwnPassApplication\Listener\SecureScheme::onDispatch
     */
    public function testOnDispatchWithValidScheme()
    {
        // Arrange
        $listener = new SecureScheme(true);

        $uri = new Uri('https://domain.com');

        $requestBuilder = $this->getMockBuilder(HttpRequest::class);
        $request = $requestBuilder->getMock();
        $request->expects($this->once())->method('getUri')->willReturn($uri);

        $eventBuilder = $this->getMockBuilder(MvcEvent::class);
        $event = $eventBuilder->getMock();
        $event->expects($this->any())->method('getRequest')->willReturn($request);
        $event->expects($this->never())->method('getResponse');

        // Act
        $listener->onDispatch($event);

        // Assert
        // ...
    }

    /**
     * @covers OwnPassApplication\Listener\SecureScheme::__construct
     * @covers OwnPassApplication\Listener\SecureScheme::onDispatch
     */
    public function testOnDispatchWithNoDevelopersMode()
    {
        // Arrange
        $listener = new SecureScheme(false);

        $uri = new Uri('https://domain.com');

        $requestBuilder = $this->getMockBuilder(HttpRequest::class);
        $request = $requestBuilder->getMock();
        $request->expects($this->once())->method('getUri')->willReturn($uri);

        $eventBuilder = $this->getMockBuilder(MvcEvent::class);
        $event = $eventBuilder->getMock();
        $event->expects($this->any())->method('getRequest')->willReturn($request);
        $event->expects($this->never())->method('getResponse');

        // Act
        $listener->onDispatch($event);

        // Assert
        // ...
    }

    /**
     * @covers OwnPassApplication\Listener\SecureScheme::__construct
     * @covers OwnPassApplication\Listener\SecureScheme::onDispatch
     */
    public function testOnDispatchWithInvalidScheme()
    {
        // Arrange
        $listener = new SecureScheme(false);

        $uri = new Uri('http://domain.com');

        $requestBuilder = $this->getMockBuilder(HttpRequest::class);
        $request = $requestBuilder->getMock();
        $request->expects($this->once())->method('getUri')->willReturn($uri);

        $response = new Response();

        $eventBuilder = $this->getMockBuilder(MvcEvent::class);
        $event = $eventBuilder->getMock();
        $event->expects($this->any())->method('getRequest')->willReturn($request);
        $event->expects($this->any())->method('getResponse')->willReturn($response);

        // Act
        $listener->onDispatch($event);
        $response = $event->getResponse();

        // Assert
        $this->assertEquals(403, $response->getStatusCode());
        $this->assertEquals('Cannot access OwnPass over HTTP scheme, HTTPS is required.', $response->getContent());
    }
}
