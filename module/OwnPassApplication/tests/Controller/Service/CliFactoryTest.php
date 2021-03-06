<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/api-server for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/api-server/master/LICENSE MIT
 */

namespace OwnPassApplicationTest\Controller\Service;

use Doctrine\ORM\EntityManagerInterface;
use Interop\Container\ContainerInterface;
use OwnPassApplication\Controller\Cli;
use OwnPassApplication\Controller\Service\CliFactory;
use PHPUnit_Framework_TestCase;
use Zend\Crypt\Password\PasswordInterface;

class CliFactoryTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers OwnPassApplication\Controller\Service\CliFactory::__invoke
     */
    public function testInvoke()
    {
        // Arrange
        $entityManager = $this->getMockBuilder(EntityManagerInterface::class)->getMockForAbstractClass();
        $crypter = $this->getMockBuilder(PasswordInterface::class)->getMockForAbstractClass();

        $container = $this->getMockBuilder(ContainerInterface::class)->getMockForAbstractClass();
        $container->expects($this->at(0))->method('get')->willReturn($entityManager);
        $container->expects($this->at(1))->method('get')->willReturn($crypter);

        $factory = new CliFactory();

        // Act
        $result = $factory($container, '', null);

        // Assert
        $this->assertInstanceOf(Cli::class, $result);
    }
}
