<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/api-server for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/api-server/master/LICENSE MIT
 */

namespace OwnPassApplicationTest\V1\Rest\Account;

use OwnPassApplication\Entity\Account;
use OwnPassApplication\V1\Rest\Account\AccountEntity;
use PHPUnit_Framework_TestCase;

class AccountEntityTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers OwnPassApplication\V1\Rest\Account\AccountEntity::__construct
     */
    public function testConstruct()
    {
        // Arrange
        $account = new Account('name', 'credential', 'email');

        // Act
        $entity = new AccountEntity($account);

        // Assert
        $this->assertEquals($account->getId(), $entity->id);
        $this->assertEquals($account->getCreationDate(), $entity->creation_date);
        $this->assertEquals($account->getUpdateDate(), $entity->update_date);
        $this->assertEquals($account->getRole(), $entity->role);
        $this->assertEquals($account->getName(), $entity->name);
    }
}
