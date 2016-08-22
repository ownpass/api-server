<?php
/**
 * This file is part of Own Pass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/ownpass for the canonical source repository
 * @copyright Copyright (c) 2016-2017 Own Pass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/ownpass/master/LICENSE MIT
 */

namespace OwnPassApplication\Rest;

use ZF\ApiProblem\ApiProblem;
use ZF\ApiProblem\ApiProblemResponse;
use ZF\Rest\AbstractResourceListener as BaseAbstractResourceListener;

class AbstractResourceListener extends BaseAbstractResourceListener
{
    protected function getAccountId()
    {
        $identity = $this->getIdentity()->getAuthenticationIdentity();

        return $identity['user_id'];
    }

    // TODO: We should move this to a listener so we don't have to call this method in every api service.
    protected function validateScope($name)
    {
        $identity = $this->getIdentity()->getAuthenticationIdentity();

        $scopes = explode(' ', $identity['scope']);

        if (!in_array($name, $scopes)) {
            return new ApiProblem(ApiProblemResponse::STATUS_CODE_403, 'Incorrect scope.');
        }
    }
}
