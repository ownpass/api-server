<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/api-server for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/api-server/master/LICENSE MIT
 */

namespace OwnPassCredentialTest\V1\Rpc\GeneratePassword;

use Interop\Container\ContainerInterface;
use OwnPassCredential\TaskService\Generator;
use OwnPassCredential\V1\Rpc\GeneratePassword\GeneratePasswordController;
use OwnPassCredential\V1\Rpc\GeneratePassword\GeneratePasswordControllerFactory;
use PHPUnit_Framework_TestCase;

class GeneratePasswordControllerFactoryTest extends PHPUnit_Framework_TestCase
{
    public function testInvoke()
    {
        // Arrange
        $generator = new Generator();

        $controllersBuilder = $this->getMockBuilder(ContainerInterface::class);
        $controllers = $controllersBuilder->getMockForAbstractClass();
        $controllers->expects($this->once())->method('get')->willReturn($generator);

        $factory = new GeneratePasswordControllerFactory();

        // Act
        $result = $factory->__invoke($controllers);

        // Assert
        $this->assertInstanceOf(GeneratePasswordController::class, $result);
    }
}
