<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/api-server for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/api-server/master/LICENSE MIT
 */

namespace OwnPassApplication\Listener;

use OwnPassApplication\Event\Notification;
use OwnPassUser\Entity\Account;
use RuntimeException;
use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventManagerInterface;
use Zend\I18n\Translator\TranslatorInterface;
use Zend\Mail\Message;
use Zend\Mail\Transport\TransportInterface;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Mime;
use Zend\Mime\Part as MimePart;
use Zend\View\Model\ViewModel;
use Zend\View\Renderer\PhpRenderer;
use Zend\View\Renderer\RendererInterface;

class EmailNotification extends AbstractListenerAggregate
{
    /**
     * @var TransportInterface
     */
    private $transport;

    /**
     * @var RendererInterface
     */
    private $renderer;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var string
     */
    private $fromAddress;

    /**
     * @var string
     */
    private $fromName;

    /**
     * @var array
     */
    private $notifications;

    public function __construct(
        TransportInterface $transport,
        RendererInterface $renderer,
        TranslatorInterface $translator,
        array $notifications
    ) {
        $this->transport = $transport;
        $this->renderer = $renderer;
        $this->translator = $translator;
        $this->notifications = $notifications;
    }

    /**
     * @param string $fromAddress
     */
    public function setFromAddress($fromAddress)
    {
        $this->fromAddress = $fromAddress;
    }

    /**
     * @param string $fromName
     */
    public function setFromName($fromName)
    {
        $this->fromName = $fromName;
    }

    /**
     * Gets the value of the "notifications" field.
     *
     * @return array
     */
    public function getNotifications()
    {
        return $this->notifications;
    }

    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $events->attach(Notification::EVENT_NOTIFY, [$this, 'onNotify'], -1);
    }

    public function onNotify(Notification $event)
    {
        foreach ($this->getNotifications() as $notification) {
            if ($notification['event'] === $event->getParam('id') && array_key_exists('email', $notification)) {
                $this->handleNotification($notification, $event);
            }
        }
    }

    private function handleNotification(array $notification, Notification $event)
    {
        $receiver = $event->getParam('receiver');
        $variables = $event->getParam('variables');
        $options = $event->getParam('options');

        $this->notifyViaEmail($receiver, $notification['email'], $variables, $options);
    }

    private function notifyViaEmail(Account $receiver, $config, array $variables, array $options)
    {
        if (!array_key_exists('template', $config)) {
            throw new RuntimeException('No template has been configured for email notification.');
        }

        if (!array_key_exists('subject', $config)) {
            throw new RuntimeException('No subject has been configured for email notification.');
        }

        $textContent = $this->renderTemplate($config['template'] . '.text.phtml', $variables, $options);
        $htmlContent = $this->renderTemplate($config['template'] . '.html.phtml', $variables, $options);

        $textPart = new MimePart($textContent);
        $textPart->type = "text/plain";
        $textPart->encoding = '7bit';
        $textPart->charset = 'UTF-8';

        $htmlPart = new MimePart($htmlContent);
        $htmlPart->type = "text/html";
        $htmlPart->encoding = '7bit';
        $htmlPart->charset = 'UTF-8';

        $body = new MimeMessage();
        $body->addPart($textPart);
        $body->addPart($htmlPart);

        $bodyPart = new MimePart($body->generateMessage());
        $bodyPart->type = Mime::MULTIPART_ALTERNATIVE . '; boundary="' . $body->getMime()->boundary() . '"';

        $mailBody = new MimeMessage();
        $mailBody->addPart($bodyPart);

        $message = new Message();
        $message->setBody($mailBody);
        $message->setTo($receiver->getEmailAddress());
        $message->setSubject($this->translator->translate($config['subject']));
        $message->setFrom($this->fromAddress, $this->fromName);

        $this->transport->send($message);
    }

    private function renderTemplate($template, array $variables, array $options)
    {
        $model = new ViewModel();
        $model->setTemplate($template);
        $model->setVariables($variables);
        $model->setOptions($options);

        return $this->renderer->render($model);
    }
}
