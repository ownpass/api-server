<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/api-server for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/api-server/master/LICENSE MIT
 */

namespace OwnPassUserTest\V1\Rest\Account;

use OwnPassUser\Entity\Account;
use OwnPassUser\V1\Rest\Account\AccountCollection;
use OwnPassUser\V1\Rest\Account\AccountEntity;
use PHPUnit_Framework_TestCase;
use Zend\Paginator\Adapter\ArrayAdapter;

class AccountCollectionTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers OwnPassUser\V1\Rest\Account\AccountCollection::build
     */
    public function testBuild()
    {
        // Arrange
        $adapter = new ArrayAdapter([
            new Account('name', 'credential', 'email'),
        ]);

        // Act
        $collection = new AccountCollection($adapter);

        // Assert
        $this->assertCount(1, $collection);
        $this->assertInstanceOf(AccountEntity::class, $collection->getItem(0));
    }
}
