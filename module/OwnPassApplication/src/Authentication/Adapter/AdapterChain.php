<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/api-server for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/api-server/master/LICENSE MIT
 */

namespace OwnPassApplication\Authentication\Adapter;

use Zend\Authentication\Adapter\ValidatableAdapterInterface;
use Zend\Authentication\Result;

class AdapterChain implements ValidatableAdapterInterface
{
    /**
     * @var string
     */
    private $identity;

    /**
     * @var string
     */
    private $credential;

    /**
     * @var ValidatableAdapterInterface[]
     */
    private $adapters;

    /**
     * Initializes a new instance of AdapterChain.
     */
    public function __construct()
    {
        $this->adapters = [];
    }

    /**
     * @param ValidatableAdapterInterface $adapter
     */
    public function addAdapter(ValidatableAdapterInterface $adapter)
    {
        $this->adapters[] = $adapter;
    }

    /**
     * @return string
     */
    public function getIdentity()
    {
        return $this->identity;
    }

    /**
     * @param string $identity
     * @return ValidatableAdapterInterface
     */
    public function setIdentity($identity)
    {
        $this->identity = $identity;
        return $this;
    }

    /**
     * @return string
     */
    public function getCredential()
    {
        return $this->credential;
    }

    /**
     * @param string $credential
     * @return ValidatableAdapterInterface
     */
    public function setCredential($credential)
    {
        $this->credential = $credential;
        return $this;
    }

    public function authenticate()
    {
        $result = new Result(Result::FAILURE_UNCATEGORIZED, (string)$this->getIdentity(), [
            'No adapters configured.',
        ]);

        foreach ($this->adapters as $adapter) {
            $adapter->setIdentity($this->getIdentity());
            $adapter->setCredential($this->getCredential());

            $result = $adapter->authenticate();

            if ($result->isValid()) {
                break;
            }
        }

        return $result;
    }
}
