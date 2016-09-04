<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/ownpass for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/ownpass/master/LICENSE MIT
 */

namespace OwnPassCredentialTest\V1\Rest\UserCredential;

use Doctrine\ORM\EntityManagerInterface;
use Interop\Container\ContainerInterface;
use OwnPassCredential\V1\Rest\UserCredential\UserCredentialResource;
use OwnPassCredential\V1\Rest\UserCredential\UserCredentialResourceFactory;
use PHPUnit_Framework_TestCase;

class UserCredentialResourceFactoryTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers OwnPassCredential\V1\Rest\UserCredential\UserCredentialResourceFactory::__invoke
     */
    public function testInvoke()
    {
        // Arrange
        $entityManagerBuilder = $this->getMockBuilder(EntityManagerInterface::class);
        $entityManager = $entityManagerBuilder->getMock();

        $servicesBuilder = $this->getMockBuilder(ContainerInterface::class);
        $services = $servicesBuilder->getMockForAbstractClass();
        $services->expects($this->once())->method('get')->willReturn($entityManager);

        $factory = new UserCredentialResourceFactory();

        // Act
        $result = $factory->__invoke($services);

        // Assert
        $this->assertInstanceOf(UserCredentialResource::class, $result);
    }
}
