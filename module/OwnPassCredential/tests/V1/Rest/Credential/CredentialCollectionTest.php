<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/ownpass for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/ownpass/master/LICENSE MIT
 */

namespace OwnPassCredentialTest\V1\Rest\Credential;

use OwnPassCredential\Entity\Credential;
use OwnPassCredential\V1\Rest\Credential\CredentialCollection;
use OwnPassCredential\V1\Rest\Credential\CredentialEntity;
use OwnPassUser\Entity\Account;
use PHPUnit_Framework_TestCase;
use Zend\Paginator\Adapter\ArrayAdapter;

class CredentialCollectionTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers OwnPassCredential\V1\Rest\Credential\CredentialCollection::build
     */
    public function testBuild()
    {
        // Arrange
        $account = new Account('', '', '', '');

        $adapter = new ArrayAdapter([
            new Credential($account, '', '', ''),
        ]);

        $collection = new CredentialCollection($adapter);

        // Act
        $result = $collection->getItem(0);

        // Assert
        $this->assertInstanceOf(CredentialEntity::class, $result);
    }
}
