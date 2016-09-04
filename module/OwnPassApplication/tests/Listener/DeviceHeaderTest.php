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
use Zend\Http\Request;
use Zend\Mvc\ApplicationInterface;
use Zend\Mvc\MvcEvent;
use Zend\Router\Http\RouteMatch;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZF\ApiProblem\ApiProblemResponse;

class DeviceHeaderTest extends PHPUnit_Framework_TestCase
{
    private $deviceHeader;
    private $serviceManager;
    private $application;
    private $routeMatch;
    private $mvcEvent;
    private $request;

    protected function setUp()
    {
        $this->deviceHeader = new DeviceHeader();

        $serviceManagerBuilder = $this->getMockBuilder(ServiceLocatorInterface::class);
        $this->serviceManager = $serviceManagerBuilder->getMockForAbstractClass();

        $applicationBuilder = $this->getMockBuilder(ApplicationInterface::class);
        $this->application = $applicationBuilder->getMockForAbstractClass();
        $this->application->method('getServiceManager')->willReturn($this->serviceManager);

        $this->routeMatch = new RouteMatch([]);
        $this->routeMatch->setMatchedRouteName('test');

        $requestBuilder = $this->getMockBuilder(Request::class);
        $this->request = $requestBuilder->getMockForAbstractClass();

        $this->mvcEvent = new MvcEvent();
        $this->mvcEvent->setApplication($this->application);
        $this->mvcEvent->setRouteMatch($this->routeMatch);
        $this->mvcEvent->setRequest($this->request);
    }

    /**
     * @covers OwnPassApplication\Listener\DeviceHeader::attach
     */
    public function testAttach()
    {
        // Arrange
        $eventsMockBuilder = $this->getMockBuilder(EventManagerInterface::class);
        $events = $eventsMockBuilder->getMockForAbstractClass();
        $events->expects($this->once())->method('attach')->with(
            $this->equalTo(MvcEvent::EVENT_DISPATCH),
            $this->equalTo([$this->deviceHeader, 'onDispatch']),
            $this->equalTo(-1)
        );

        // Act
        $this->deviceHeader->attach($events);

        // Assert
        // ...
    }

    /**
     * @covers OwnPassApplication\Listener\DeviceHeader::onDispatch
     */
    public function testOnDispatchWithConsoleRequest()
    {
        // Arrange
        $requestBuilder = $this->getMockBuilder(ConsoleRequest::class);
        $request = $requestBuilder->getMockForAbstractClass();

        $eventBuilder = $this->getMockBuilder(MvcEvent::class);
        $event = $eventBuilder->getMock();
        $event->expects($this->never())->method('getApplication');
        $event->expects($this->once())->method('getRequest')->willReturn($request);

        // Act
        $this->deviceHeader->onDispatch($event);

        // Assert
        // ...
    }

    /**
     * @covers OwnPassApplication\Listener\DeviceHeader::onDispatch
     */
    public function testOnDispatchWithInvalidRoute()
    {
        // Arrange
        $this->serviceManager->expects($this->once())->method('get')->with($this->equalTo('config'))->willReturn([
            'router' => [
                'routes' => [],
            ],
        ]);

        // Act
        $this->deviceHeader->onDispatch($this->mvcEvent);

        // Assert
        // ...
    }

    /**
     * @covers OwnPassApplication\Listener\DeviceHeader::onDispatch
     */
    public function testOnDispatchWithNoRouteConfiguration()
    {
        // Arrange
        $this->serviceManager->expects($this->once())->method('get')->with($this->equalTo('config'))->willReturn([
            'router' => [
                'routes' => [
                    'test' => [
                        'options' => [],
                    ],
                ],
            ],
        ]);

        // Act
        $this->deviceHeader->onDispatch($this->mvcEvent);

        // Assert
        // ...
    }

    /**
     * @covers OwnPassApplication\Listener\DeviceHeader::onDispatch
     * @covers OwnPassApplication\Listener\DeviceHeader::buildErrorResponse
     */
    public function testOnDispatchWithNoDeviceHeader()
    {
        // Arrange
        $this->serviceManager->expects($this->once())->method('get')->with($this->equalTo('config'))->willReturn([
            'router' => [
                'routes' => [
                    'test' => [
                        'options' => [
                            'device_required' => true,
                        ],
                    ],
                ],
            ],
        ]);

        // Act
        $this->deviceHeader->onDispatch($this->mvcEvent);

        // Assert
        $this->assertInstanceOf(ApiProblemResponse::class, $this->mvcEvent->getResponse());
    }
}
