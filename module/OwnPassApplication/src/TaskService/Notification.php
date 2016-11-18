<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/api-server for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/api-server/master/LICENSE MIT
 */

namespace OwnPassApplication\TaskService;

use OwnPassApplication\Entity\Device;
use OwnPassApplication\Event\Notification as NotificationEvent;
use OwnPassUser\Entity\Account;
use OwnPassUser\Entity\Identity;
use Zend\EventManager\EventManagerInterface;

class Notification
{
    /**
     * @var EventManagerInterface
     */
    private $eventManager;

    /**
     * @var array
     */
    private $config;

    public function __construct(EventManagerInterface $eventManager, array $config)
    {
        $this->eventManager = $eventManager;
        $this->config = $config;
    }

    /**
     * @return EventManagerInterface
     */
    public function getEventManager()
    {
        return $this->eventManager;
    }

    public function notifyUserOfNewDevice(Account $user, Device $device, $activationCode)
    {
        $this->notify('device-created', $user, [
            'account' => $user,
            'device' => $device,
            'activationCode' => $activationCode,
            'controlPanelUrl' => $this->config['ownpass_security']['control_panel_url'],
        ]);
    }

    public function notifyNewUser(Account $account, Identity $identity)
    {
        $this->notify('account-created', $account, [
            'account' => $account,
            'identity' => $identity,
        ]);
    }

    public function notifyAdminsOfNewUser(Account $account, Identity $identity)
    {
        // @todo Notify all admins that a new account has been created.
    }

    public function notifyAccountActivate(Account $account, $credential)
    {
        $this->notify('account-activate', $account, [
            'account' => $account,
            'credential' => $credential,
        ]);
    }

    public function notifyAccountDeactivate(Account $account)
    {
        $this->notify('account-deactivate', $account, [
            'account' => $account,
        ]);
    }

    public function notify($notificationId, Account $receiver, array $variables = [], array $options = [])
    {
        $this->eventManager->triggerEvent(new NotificationEvent(NotificationEvent::EVENT_NOTIFY, $this, [
            'id' => $notificationId,
            'receiver' => $receiver,
            'variables' => $variables,
            'options' => $options,
        ]));
    }
}
