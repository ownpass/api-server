<?php
/**
 * This file is part of Own Pass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/ownpass for the canonical source repository
 * @copyright Copyright (c) 2016-2017 Own Pass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/ownpass/master/LICENSE MIT
 */

namespace OwnPassUser\V1\Rest\Account;

use OwnPassApplication\Paginator\AbstractProxy;

class AccountCollection extends AbstractProxy
{
    protected function build($key, $value)
    {
        return new AccountEntity($value);
    }
}
