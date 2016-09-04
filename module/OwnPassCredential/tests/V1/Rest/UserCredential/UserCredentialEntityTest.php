<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/ownpass for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/ownpass/master/LICENSE MIT
 */

namespace OwnPassCredentialTest\V1\Rest\UserCredential;

use OwnPassCredential\Entity\Credential;
use OwnPassCredential\V1\Rest\UserCredential\UserCredentialEntity;
use OwnPassUser\Entity\Account;
use OwnPassUser\V1\Rest\Account\AccountEntity;
use PHPUnit_Framework_TestCase;
use Ramsey\Uuid\UuidInterface;

class UserCredentialEntityTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers OwnPassCredential\V1\Rest\UserCredential\UserCredentialEntity::__construct
     */
    public function testConstructor()
    {
        // Arrange
        $account = new Account('', '', '', '');
        $credential = new Credential($account, 'url', 'identity', 'credential');

        // Act
        $entity = new UserCredentialEntity($credential);

        // Assert
        $this->assertInstanceOf(UuidInterface::class, $entity->id);
        $this->assertInstanceOf(AccountEntity::class, $entity->account);
        $this->assertEquals('url', $entity->urlRaw);
        $this->assertEquals('identity', $entity->identity);
        $this->assertEquals('credential', $entity->credential);
    }
}
