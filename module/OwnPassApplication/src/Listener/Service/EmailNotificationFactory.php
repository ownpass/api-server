<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/ownpass for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/ownpass/master/LICENSE MIT
 */

namespace OwnPassApplication\Listener\Service;

use Interop\Container\ContainerInterface;
use OwnPassApplication\Listener\EmailNotification;
use RuntimeException;
use Zend\I18n\Translator\TranslatorInterface;
use Zend\Mail\Transport\Smtp;
use Zend\Mail\Transport\SmtpOptions;
use Zend\ServiceManager\Factory\FactoryInterface;

class EmailNotificationFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('config');
        $emailConfig = $config['ownpass_email'];

        $renderer = $container->get('ViewRenderer');
        $translator = $container->get(TranslatorInterface::class);
        $transport = $this->buildEmailTransport($emailConfig['transport']);

        $listener = new EmailNotification($transport, $renderer, $translator);
        $listener->setFromAddress($emailConfig['from_address']);
        $listener->setFromName($emailConfig['from_name']);

        return $listener;
    }

    private function buildEmailTransport($config)
    {
        $transportOptions = $config['options'];

        switch ($config['type']) {
            case 'smtp':
                $transport = new Smtp(new SmtpOptions($transportOptions));
                break;

            default:
                throw new RuntimeException('Invalid transport type: ' . $config['type']);
        }

        return $transport;
    }
}
