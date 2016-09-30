<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/ownpass for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/ownpass/master/LICENSE MIT
 */

namespace OwnPassOAuthTest\Controller\Service;

use Doctrine\ORM\EntityManagerInterface;
use Interop\Container\ContainerInterface;
use OwnPassOAuth\Controller\Cli;
use OwnPassOAuth\Controller\Service\CliFactory;
use PHPUnit_Framework_TestCase;

class CliFactoryTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers OwnPassOAuth\Controller\Service\CliFactory::__invoke
     */
    public function testInvoke()
    {
        // Arrange
        $entityManager = $this->getMockBuilder(EntityManagerInterface::class)->getMockForAbstractClass();

        $container = $this->getMockBuilder(ContainerInterface::class)->getMockForAbstractClass();
        $container->method('get')->willReturn($entityManager);

        $factory = new CliFactory();

        // Act
        $result = $factory($container, '', null);

        // Assert
        $this->assertInstanceOf(Cli::class, $result);
    }
}
