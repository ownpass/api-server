<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/api-server for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/api-server/master/LICENSE MIT
 */

namespace OwnPassApplication\V1\Rest\Device;

use OwnPassApplication\Entity\Device;
use OwnPassApplication\V1\Rest\Account\AccountEntity;

class DeviceEntity
{
    const STATE_ACTIVATED = 'activated';
    const STATE_NOT_ACTIVATED = 'not-activated';

    public $id;
    public $account;
    public $state;
    public $name;
    public $description;
    public $remote_address;
    public $user_agent;

    public function __construct(Device $device)
    {
        $this->id = $device->getId();
        $this->account = new AccountEntity($device->getAccount());
        $this->state = $device->getActivationCode() ? self::STATE_NOT_ACTIVATED : self::STATE_ACTIVATED;
        $this->name = $device->getName();
        $this->description = $device->getDescription();
        $this->remote_address = $device->getRemoteAddress();
        $this->user_agent = $device->getUserAgent();
    }
}
