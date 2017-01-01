<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/api-server for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/api-server/master/LICENSE MIT
 */

namespace OwnPassCredentialTest\V1\Rest\UserCredential;

use OwnPassCredential\Entity\Credential;
use OwnPassCredential\V1\Rest\UserCredential\UserCredentialCollection;
use OwnPassCredential\V1\Rest\UserCredential\UserCredentialEntity;
use OwnPassApplication\Entity\Account;
use PHPUnit_Framework_TestCase;
use Zend\Paginator\Adapter\ArrayAdapter;

class UserCredentialCollectionTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers OwnPassCredential\V1\Rest\UserCredential\UserCredentialCollection::build
     */
    public function testBuild()
    {
        // Arrange
        $account = new Account('', '', '', '');

        $adapter = new ArrayAdapter([
            new Credential($account, '', '', ''),
        ]);

        $collection = new UserCredentialCollection($adapter);

        // Act
        $result = $collection->getItem(0);

        // Assert
        $this->assertInstanceOf(UserCredentialEntity::class, $result);
    }
}
