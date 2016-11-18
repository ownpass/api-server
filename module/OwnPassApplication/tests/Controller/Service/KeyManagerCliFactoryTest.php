<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/api-server for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/api-server/master/LICENSE MIT
 */

namespace OwnPassApplicationTest\Controller\Service;

use Interop\Container\ContainerInterface;
use OwnPassApplication\Controller\KeyManagerCli;
use OwnPassApplication\Controller\Service\KeyManagerCliFactory;
use OwnPassApplication\TaskService\KeyManager;
use PHPUnit_Framework_TestCase;

class KeyManagerCliFactoryTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers OwnPassApplication\Controller\Service\KeyManagerCliFactory::__invoke
     */
    public function testInvoke()
    {
        // Arrange
        $keyManager = new KeyManager([]);

        $containerBuilder = $this->getMockBuilder(ContainerInterface::class);
        $container = $containerBuilder->getMockForAbstractClass();
        $container->expects($this->once())->method('get')->willReturn($keyManager);

        $service = new KeyManagerCliFactory();

        // Act
        $result = $service->__invoke($container, '', []);

        // Assert
        $this->assertInstanceOf(KeyManagerCli::class, $result);
    }
}
