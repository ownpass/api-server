<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/api-server for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/api-server/master/LICENSE MIT
 */

namespace OwnPassCredential\TaskService;

use Zend\Math\Rand;

class Generator
{
    /**
     * @var int
     */
    private $length;

    /**
     * @var bool
     */
    private $useLowercase;

    /**
     * @var bool
     */
    private $useUppercase;

    /**
     * @var bool
     */
    private $useDigits;

    /**
     * @var bool
     */
    private $useSymbols;

    /**
     * Initializes a new instance of this class.
     */
    public function __construct()
    {
        $this->length = 8;
        $this->useLowercase = true;
        $this->useUppercase = true;
        $this->useDigits = true;
        $this->useSymbols = true;
    }

    /**
     * @return string
     */
    public function generate()
    {
        $charlist = '';

        if ($this->getUseLowercase()) {
            $charlist .= 'abcdefghijklmnopqrstuvwxyz';
        }

        if ($this->getUseUppercase()) {
            $charlist .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        }

        if ($this->getUseDigits()) {
            $charlist .= '0123456789';
        }

        if ($this->getUseSymbols()) {
            $charlist .= '!@#$%^&*()';
        }

        return Rand::getString($this->getLength(), $charlist);
    }

    /**
     * @return int
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * @param int $length
     */
    public function setLength($length)
    {
        $this->length = $length;
    }

    /**
     * @return boolean
     */
    public function getUseLowercase()
    {
        return $this->useLowercase;
    }

    /**
     * @param boolean $useLowercase
     */
    public function setUseLowercase($useLowercase)
    {
        $this->useLowercase = $useLowercase;
    }

    /**
     * @return boolean
     */
    public function getUseUppercase()
    {
        return $this->useUppercase;
    }

    /**
     * @param boolean $useUppercase
     */
    public function setUseUppercase($useUppercase)
    {
        $this->useUppercase = $useUppercase;
    }

    /**
     * @return boolean
     */
    public function getUseDigits()
    {
        return $this->useDigits;
    }

    /**
     * @param boolean $useDigits
     */
    public function setUseDigits($useDigits)
    {
        $this->useDigits = $useDigits;
    }

    /**
     * @return boolean
     */
    public function getUseSymbols()
    {
        return $this->useSymbols;
    }

    /**
     * @param boolean $useSymbols
     */
    public function setUseSymbols($useSymbols)
    {
        $this->useSymbols = $useSymbols;
    }
}
