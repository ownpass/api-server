<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/ownpass for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/ownpass/master/LICENSE MIT
 */

namespace OwnPassApplication\TaskService;

use Zend\Crypt\PublicKey\RsaOptions;

class KeyManager
{
    /**
     * @var array
     */
    private $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function generate()
    {
        $rsaOptions = new RsaOptions([
            'pass_phrase' => $this->config['password'],
        ]);

        $rsaOptions->generateKeys([
            'private_key_bits' => $this->config['private_key_bits'],
        ]);

        file_put_contents($this->config['private_key'], $rsaOptions->getPrivateKey());
        file_put_contents($this->config['public_key'], $rsaOptions->getPublicKey());
    }

    public function remove()
    {
        if (is_file($this->config['private_key'])) {
            unlink($this->config['private_key']);
        }

        if (is_file($this->config['public_key'])) {
            unlink($this->config['public_key']);
        }
    }
}
