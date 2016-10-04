<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/ownpass for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/ownpass/master/LICENSE MIT
 */

namespace OwnPassUser\Controller;

use Zend\Authentication\AuthenticationServiceInterface;
use Zend\Form\FormInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Session\AbstractContainer;
use Zend\Uri\Http;
use Zend\Uri\Uri;
use Zend\Uri\UriFactory;
use Zend\View\Model\ViewModel;

/**
 * @codeCoverageIgnore
 */
class Authenticate extends AbstractActionController
{
    /**
     * @var AuthenticationServiceInterface
     */
    private $authenticationService;

    /**
     * @var AbstractContainer
     */
    private $session;

    /**
     * @var FormInterface
     */
    private $loginForm;

    public function __construct(
        AuthenticationServiceInterface $authenticationService,
        AbstractContainer $session,
        FormInterface $loginForm
    ) {
        $this->authenticationService = $authenticationService;
        $this->session = $session;
        $this->loginForm = $loginForm;
    }

    public function loginAction()
    {
        $redirect = $this->params()->fromQuery('redirect');
        if ($redirect) {
            $redirectUrl = UriFactory::factory($redirect);

            // When the host is not null, the user is trying to request an invalid url!
            if ($redirectUrl->getHost() === null) {
                $this->session->redirect = $redirect;
            } else {
                $this->session->redirect = null;
            }

            return $this->redirect()->toRoute('login');
        }

        if ($this->authenticationService->hasIdentity()) {
            $response = $this->getResponse();
            $response->setContent('You are already logged in.');

            return $response;
        }

        if ($this->getRequest()->isPost()) {
            $this->loginForm->setData($this->getRequest()->getPost());

            if ($this->loginForm->isValid()) {
                if ($this->session->redirect) {
                    return $this->redirect()->toUrl($this->session->redirect);
                }

                $response = $this->getResponse();
                $response->setContent('You successfully logged in.');

                return $response;
            }
        }

        return new ViewModel([
            'loginForm' => $this->loginForm,
        ]);
    }

    public function logoutAction()
    {
        $this->session->redirect = null;

        $this->authenticationService->clearIdentity();

        return $this->redirect()->toRoute('login');
    }
}
