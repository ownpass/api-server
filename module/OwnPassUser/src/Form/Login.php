<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/api-server for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/api-server/master/LICENSE MIT
 */

namespace OwnPassUser\Form;

use Zend\Form\Form;

class Login extends Form
{
    public function init()
    {
        $this->add([
            'type' => 'text',
            'name' => 'identity',
            'options' => [
                'label' => 'Username',
            ],
            'attributes' => [
                'tabindex' => 1,
            ],
        ]);

        $this->add([
            'type' => 'password',
            'name' => 'credential',
            'options' => [
                'label' => 'Password',
            ],
            'attributes' => [
                'tabindex' => 2,
            ],
        ]);

        $this->add([
            'type' => 'submit',
            'name' => 'submit',
            'attributes' => [
                'tabindex' => 3,
                'value' => 'Login',
            ],
        ]);
    }
}
