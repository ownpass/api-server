<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/ownpass for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/ownpass/master/LICENSE MIT
 */

namespace OwnPassUserTest\TaskService\Service;

use Interop\Container\ContainerInterface;
use OwnPassUser\TaskService\Account;
use OwnPassUser\TaskService\Service\AccountFactory;
use PHPUnit_Framework_TestCase;

class AccountFactoryTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers OwnPassUser\TaskService\Service\AccountFactory::__invoke
     */
    public function testInvoke()
    {
        // Arrange
        $factory = new AccountFactory();

        $container = $this->getMockBuilder(ContainerInterface::class)->getMockForAbstractClass();

        // Act
        $result = $factory($container, '', null);

        // Assert
        $this->assertInstanceOf(Account::class, $result);
    }
}
