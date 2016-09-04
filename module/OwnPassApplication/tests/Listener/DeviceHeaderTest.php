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
use PHPUnit_Framework_TestCase;
use Zend\Console\Request as ConsoleRequest;
use Zend\EventManager\EventManagerInterface;
use Zend\Mvc\ApplicationInterface;
use Zend\Mvc\MvcEvent;
use Zend\Router\Http\RouteMatch;
use Zend\ServiceManager\ServiceLocatorInterface;

class DeviceHeaderTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers OwnPassApplication\Listener\DeviceHeader::attach
     */
    public function testAttach()
    {
        // Arrange
        $deviceHeader = new DeviceHeader();

        $eventsMockBuilder = $this->getMockBuilder(EventManagerInterface::class);
        $events = $eventsMockBuilder->getMockForAbstractClass();
        $events->expects($this->once())->method('attach')->with(
            $this->equalTo(MvcEvent::EVENT_DISPATCH),
            $this->equalTo([$deviceHeader, 'onDispatch']),
            $this->equalTo(-1)
        );

        // Act
        $deviceHeader->attach($events);

        // Assert
        //$this->assertInstanceOf(UuidInterface::class, $result);
    }

    /**
     * @covers OwnPassApplication\Listener\DeviceHeader::onDispatch
     */
    public function testOnDispatchWithConsoleRequest()
    {
        // Arrange
        $deviceHeader = new DeviceHeader();

        $requestBuilder = $this->getMockBuilder(ConsoleRequest::class);
        $request = $requestBuilder->getMockForAbstractClass();

        $event = $this->getMockBuilder(MvcEvent::class);
        $event = $event->getMock();
        $event->expects($this->never())->method('getApplication');
        $event->expects($this->once())->method('getRequest')->willReturn($request);

        // Act
        $deviceHeader->onDispatch($event);

        // Assert
        // ...
    }

    public function testOnDispatchWithInvalidRoute()
    {
        // Arrange
        $deviceHeader = new DeviceHeader();

        $serviceManager = $this->getMockBuilder(ServiceLocatorInterface::class);
        $serviceManager = $serviceManager->getMockForAbstractClass();
        $serviceManager->method('get')->with($this->equalTo('config'))->willReturn([
            'router' => [
                'routes' => [],
            ],
        ]);

        $application = $this->getMockBuilder(ApplicationInterface::class);
        $application = $application->getMockForAbstractClass();
        $application->method('getServiceManager')->willReturn($serviceManager);

        $routeMatch = new RouteMatch([]);
        $routeMatch->setMatchedRouteName('test');

        $event = new MvcEvent();
        $event->setApplication($application);
        $event->setRouteMatch($routeMatch);

        // Act
        $deviceHeader->onDispatch($event);

        // Assert
        // ...
    }

    public function testOnDispatchWithNoRouteConfiguration()
    {
    }

    public function testOnDispatchWithNoDeviceHeader()
    {
    }
}
