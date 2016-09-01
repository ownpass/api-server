<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/ownpass for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/ownpass/master/LICENSE MIT
 */

namespace OwnPassApplication\Paginator;

use Zend\Paginator\Adapter\AdapterInterface;
use Zend\Paginator\Adapter\Callback;
use Zend\Paginator\Paginator;

abstract class AbstractProxy extends Paginator
{
    /**
     * @var AdapterInterface
     */
    protected $proxy;

    public function __construct(AdapterInterface $adapter)
    {
        $this->proxy = $adapter;

        parent::__construct(new Callback(
            [$this, 'onGetItems'],
            [$this, 'onCount']
        ));
    }

    public function onCount()
    {
        return $this->proxy->count();
    }

    public function onGetItems($offset, $itemCountPerPage)
    {
        $result = [];

        foreach ($this->proxy->getItems($offset, $itemCountPerPage) as $key => $value) {
            $result[] = $this->build($key, $value);
        }

        return $result;
    }

    abstract protected function build($key, $value);
}
