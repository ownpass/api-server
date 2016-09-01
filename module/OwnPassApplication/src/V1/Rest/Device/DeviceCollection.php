<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/ownpass for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/ownpass/master/LICENSE MIT
 */

namespace OwnPassApplication\V1\Rest\Device;

use OwnPassApplication\Paginator\AbstractProxy;

class DeviceCollection extends AbstractProxy
{
    protected function build($key, $value)
    {
        return new DeviceEntity($value);
    }
}
