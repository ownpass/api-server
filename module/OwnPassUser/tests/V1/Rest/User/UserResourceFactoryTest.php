<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/ownpass for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/ownpass/master/LICENSE MIT
 */

namespace OwnPassUserTest\V1\Rest\User;

use Doctrine\ORM\EntityManagerInterface;
use Interop\Container\ContainerInterface;
use OwnPassUser\V1\Rest\User\UserResource;
use OwnPassUser\V1\Rest\User\UserResourceFactory;
use PHPUnit_Framework_TestCase;

class UserResourceFactoryTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers OwnPassUser\V1\Rest\User\UserResourceFactory::__invoke
     */
    public function testInvoke()
    {
        // Arrange
        $factory = new UserResourceFactory();

        $entityManager = $this->getMockBuilder(EntityManagerInterface::class)->getMockForAbstractClass();

        $container = $this->getMockBuilder(ContainerInterface::class)->getMockForAbstractClass();
        $container->expects($this->at(0))->method('get')->willReturn($entityManager);

        // Act
        $result = $factory($container, '', null);

        // Asserts
        $this->assertInstanceOf(UserResource::class, $result);
    }
}
