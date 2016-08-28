<?php
/**
 * This file is part of Own Pass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/ownpass for the canonical source repository
 * @copyright Copyright (c) 2016-2017 Own Pass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/ownpass/master/LICENSE MIT
 */

namespace OwnPassApplication\V1\Rest\Device;

use OwnPassApplication\Entity\Device;
use OwnPassUser\V1\Rest\Account\AccountEntity;

class DeviceEntity
{
    public $id;
    public $account;
    public $user_agent;
    public $activation_code;
    public $name;
    public $description;

    public function __construct(Device $device)
    {
        $this->id = $device->getId();
        $this->account = new AccountEntity($device->getAccount());
        $this->name = $device->getName();
        $this->description = $device->getDescription();
        $this->user_agent = $device->getUserAgent();
        $this->activation_code = $device->getActivationCode();
    }
}
