<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/api-server for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/api-server/master/LICENSE MIT
 */

namespace OwnPassCredential\V1\Rpc\GeneratePassword;

use OwnPassCredential\TaskService\Generator;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;

class GeneratePasswordController extends AbstractActionController
{
    private $generator;

    public function __construct(Generator $generator)
    {
        $this->generator = $generator;
    }

    public function generatePasswordAction()
    {
        $inputFilter = $this->getEvent()->getParam('ZF\ContentValidation\InputFilter');
        $options = $inputFilter->getValues();

        $this->generator->setLength($options['length']);
        $this->generator->setUseLowercase($options['lowercase']);
        $this->generator->setUseUppercase($options['uppercase']);
        $this->generator->setUseDigits($options['digits']);
        $this->generator->setUseSymbols($options['symbols']);

        return new JsonModel([
            'result' => $this->generator->generate(),
            'length' => $options['length'],
            'lowercase' => $options['lowercase'],
            'digits' => $options['digits'],
            'symbols' => $options['symbols'],
        ]);
    }
}
