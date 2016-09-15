<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/ownpass for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/ownpass/master/LICENSE MIT
 */

namespace OwnPassApplication\TaskService;

use OwnPassUser\Entity\Account;
use RuntimeException;
use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;
use Zend\I18n\Translator\TranslatorInterface;
use Zend\View\Model\ViewModel;
use Zend\View\Renderer\RendererInterface;

class Notification
{
    /**
     * @var array
     */
    private $notifications;

    /**
     * @var array
     */
    private $emailConfig;

    /**
     * @var RendererInterface
     */
    private $renderer;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var Swift_Mailer
     */
    private $mailer;

    public function __construct(array $config, RendererInterface $renderer, TranslatorInterface $translator)
    {
        $this->notifications = $config['ownpass_notifications'];
        $this->emailConfig = $config['ownpass_email'];
        $this->renderer = $renderer;
        $this->translator = $translator;
        $this->mailer = Swift_Mailer::newInstance($this->buildTransport());

    }

    public function notify($notificationId, Account $receiver, array $variables = [], array $options = [])
    {
        if (!array_key_exists($notificationId, $this->notifications)) {
            throw new RuntimeException(sprintf('A notification with id "%s" does not exists.', $notificationId));
        }

        $config = $this->notifications[$notificationId];

        if (array_key_exists('email', $config)) {
            $this->notifyViaEmail($receiver, $config['email'], $variables, $options);
        }
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

        $message = Swift_Message::newInstance();
        $message->setSubject($this->translator->translate($config['subject']));
        $message->setFrom($this->emailConfig['from_address'], $this->emailConfig['from_name']);
        $message->setTo($receiver->getEmailAddress());
        $message->setBody($textContent);
        $message->addPart($htmlContent, 'text/html');

        $this->mailer->send($message);
    }

    private function renderTemplate($template, array $variables, array $options)
    {
        $model = new ViewModel();
        $model->setTemplate($template);
        $model->setVariables($variables);
        $model->setOptions($options);

        return $this->renderer->render($model);
    }

    private function buildTransport()
    {
        $transportOptions = $this->emailConfig['transport']['options'];

        switch ($this->emailConfig['transport']['type']) {
            case 'smtp':
                $transport = Swift_SmtpTransport::newInstance($transportOptions['host'], $transportOptions['port']);
                $transport->setUsername($transportOptions['username']);
                $transport->setPassword($transportOptions['password']);
                break;

            default:
                throw new RuntimeException('Invalid transport type: ' . $this->emailConfig['transport']['type']);
        }

        return $transport;
    }
}
