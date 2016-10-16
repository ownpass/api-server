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
use Zend\EventManager\EventManagerInterface;

class Notification
{
    /**
     * @var EventManagerInterface
     */
    private $eventManager;

    public function __construct(EventManagerInterface $eventManager)
    {
        $this->eventManager = $eventManager;
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
        $this->eventManager->triggerEvent(new NotificationEvent(NotificationEvent::EVENT_NOTIFY, $this, [
            'id' => $notificationId,
            'receiver' => $receiver,
            'variables' => $variables,
            'options' => $options,
        ]));
    }
}
