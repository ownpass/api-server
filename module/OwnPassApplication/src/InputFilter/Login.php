<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/api-server for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/api-server/master/LICENSE MIT
 */

namespace OwnPassApplication\InputFilter;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Validator\Authentication;
use Zend\InputFilter\InputFilter;

class Login extends InputFilter
{
    /**
     * @var AuthenticationService
     */
    private $authenticationService;

    // @todo Use AuthenticationServiceInterface once it defines the getAdapter method.
    public function __construct(AuthenticationService $authenticationService)
    {
        $this->authenticationService = $authenticationService;
    }

    public function init()
    {
        $this->add([
            'name' => 'identity',
            'required' => true,
        ]);

        $this->add([
            'name' => 'credential',
            'required' => true,
            'validators' => [
                [
                    'name' => Authentication::class,
                    'options' => [
                        'adapter' => $this->authenticationService->getAdapter(),
                        'service' => $this->authenticationService,
                        'identity' => 'identity',
                        'credential' => 'credential',
                    ],
                ],
            ],
        ]);
    }
}
