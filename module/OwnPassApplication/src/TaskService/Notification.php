<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/ownpass for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/ownpass/master/LICENSE MIT
 */

namespace OwnPassApplication\TaskService;

use OwnPassApplication\Event\Notification as NotificationEvent;
use OwnPassUser\Entity\Account;
use RuntimeException;
use Zend\EventManager\EventManagerInterface;

class Notification
{
    /**
     * @var array
     */
    private $notifications;

    /**
     * @var EventManagerInterface
     */
    private $eventManager;

    public function __construct(array $notifications, EventManagerInterface $eventManager)
    {
        $this->notifications = $notifications;
        $this->eventManager = $eventManager;
    }

    /**
     * @return array
     */
    public function getNotifications()
    {
        return $this->notifications;
    }

    /**
     * @return EventManagerInterface
     */
    public function getEventManager()
    {
        return $this->eventManager;
    }

    public function notify($notificationId, Account $receiver, array $variables = [], array $options = [])
    {
        if (!array_key_exists($notificationId, $this->notifications)) {
            throw new RuntimeException(sprintf('A notification with id "%s" does not exists.', $notificationId));
        }

        $config = $this->notifications[$notificationId];

        $this->eventManager->triggerEvent(new NotificationEvent(NotificationEvent::EVENT_NOTIFY, $this, [
            'id' => $notificationId,
            'receiver' => $receiver,
            'config' => $config,
            'variables' => $variables,
            'options' => $options,
        ]));
    }
}
