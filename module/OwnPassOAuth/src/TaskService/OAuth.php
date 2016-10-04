<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/ownpass for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/ownpass/master/LICENSE MIT
 */

namespace OwnPassOAuth\TaskService;

use Doctrine\ORM\EntityManager;
use OAuth2\Request as OAuthRequest;
use OAuth2\Response as OAuthResponse;
use OAuth2\Server;
use OwnPassOAuth\Entity\Application;
use OwnPassOAuth\Entity\AuthorizedApplication;
use OwnPassUser\Entity\Account;
use OwnPassUser\Entity\Identity;
use Ramsey\Uuid\Uuid;
use RuntimeException;
use SimpleXMLElement;
use Zend\Http\PhpEnvironment\Request as PhpEnvironmentRequest;
use Zend\Http\Request as HttpRequest;
use Zend\Http\Response as HttpResponse;

class OAuth
{
    /**
     * @var Server
     */
    private $server;

    /**
     * @var EntityManager
     */
    private $entityManager;

    public function __construct(Server $server, EntityManager $entityManager)
    {
        $this->server = $server;
        $this->entityManager = $entityManager;
    }

    public function getApplication($clientId)
    {
        if (!$clientId) {
            return null;
        }

        return $this->server->getStorage('client')->getApplication($clientId);
    }

    public function getAccessToken(HttpRequest $httpRequest)
    {
        $oauthRequest = $this->buildRequest($httpRequest);

        return $this->server->getAccessTokenData($oauthRequest);
    }

    public function isApplicationAuthorized(Application $application, $accountId)
    {
        if (!$accountId) {
            return false;
        }

        $repository = $this->entityManager->getRepository(AuthorizedApplication::class);

        $authorizedApplication = $repository->findOneBy([
            'application' => $application->getClientId(),
            'account' => $accountId,
        ]);

        return $authorizedApplication !== null;
    }

    public function authorizeApplication(Application $application, $accountId)
    {
        if ($this->isApplicationAuthorized($application, $accountId)) {
            return;
        }

        $repository = $this->entityManager->getRepository(Account::class);
        $account = $repository->find($accountId);

        if (!$account) {
            throw new RuntimeException('Invalid identity id provided.');
        }

        $authorizedAppplication = new AuthorizedApplication($account, $application, null);

        $this->entityManager->persist($authorizedAppplication);
        $this->entityManager->flush($authorizedAppplication);
    }

    public function verifyResourceRequest(HttpRequest $httpRequest)
    {
        $oauthRequest = $this->buildRequest($httpRequest);

        $this->server->verifyResourceRequest($oauthRequest, null);

        return $this->buildResponse(
            $this->determineFormat($httpRequest),
            new HttpResponse(),
            $this->server->getResponse()
        );
    }

    public function handleAuthorizeRequest(HttpRequest $httpRequest, HttpResponse $httpResponse, $isAuthorized, $userId)
    {
        $format = $this->determineFormat($httpRequest);

        $oauthRequest = $this->buildRequest($httpRequest);
        $oauthResponse = new OAuthResponse();

        $isValid = $this->server->validateAuthorizeRequest($oauthRequest, $oauthResponse);
        if (!$isValid) {
            return $this->buildResponse($format, $httpResponse, $oauthResponse);
        }

        $oauthResponse = $this->server->handleAuthorizeRequest($oauthRequest, $oauthResponse, $isAuthorized, $userId);

        return $this->buildResponse($format, $httpResponse, $oauthResponse);
    }

    public function handleTokenRequest(HttpRequest $httpRequest, HttpResponse $httpResponse)
    {
        $oauthRequest = $this->buildRequest($httpRequest);

        $oauthResponse = $this->server->handleTokenRequest($oauthRequest);

        $format = $this->determineFormat($httpRequest);

        return $this->buildResponse($format, $httpResponse, $oauthResponse);
    }

    private function buildRequest(HttpRequest $httpRequest)
    {
        $headers = $httpRequest->getHeaders();

        // Marshal content type, so we can seed it into the $_SERVER array
        $contentType = $headers->has('Content-Type') ? $headers->get('Content-Type')->getFieldValue() : '';

        // Get $_SERVER superglobal
        $server = [];
        if ($httpRequest instanceof PhpEnvironmentRequest) {
            $server = $httpRequest->getServer()->toArray();
        } elseif (!empty($_SERVER)) {
            $server = $_SERVER;
        }
        $server['REQUEST_METHOD'] = $httpRequest->getMethod();

        // Seed headers with HTTP auth information
        $headers = $headers->toArray();
        if (isset($server['PHP_AUTH_USER'])) {
            $headers['PHP_AUTH_USER'] = $server['PHP_AUTH_USER'];
        }
        if (isset($server['PHP_AUTH_PW'])) {
            $headers['PHP_AUTH_PW'] = $server['PHP_AUTH_PW'];
        }

        $bodyParams = $this->getBodyParams($httpRequest);

        return new OAuthRequest(
            $httpRequest->getQuery()->toArray(),
            $bodyParams,
            [],
            [],
            [],
            $server,
            $httpRequest->getContent(),
            $headers
        );
    }

    private function getBodyParams($request)
    {
        $contentType = $request->getHeader('Content-Type');

        if ($contentType && $contentType->match('application/json')) {
            $bodyParams = $this->decodeJson($request->getContent());
        } else {
            $bodyParams = $request->getPost()->toArray();
        }

        return $bodyParams;
    }

    private function buildResponse($format, HttpResponse $httpResponse, OAuthResponse $oauthResponse)
    {
        $httpResponse->setVersion($oauthResponse->version);
        $httpResponse->setStatusCode($oauthResponse->getStatusCode());

        $headers = $httpResponse->getHeaders();

        foreach ($oauthResponse->getHttpHeaders() as $name => $value) {
            $headers->addHeaderLine(sprintf('%s: %s', $name, $value));
        }

        switch ($format) {
            case 'json':
                $headers->addHeaderLine('Content-Type: application/json');
                $httpResponse->setContent(json_encode($oauthResponse->getParameters()));
                break;

            case 'xml':
                $headers->addHeaderLine('Content-Type: text/xml');

                $xml = new SimpleXMLElement('<response/>');
                foreach ($oauthResponse->getParameters() as $key => $param) {
                    $xml->addChild($key, $param);
                }

                $httpResponse->setContent($xml->asXML());
                break;

            default:
                throw new RuntimeException('Invalid format provided: ' . $format);
        }

        return $httpResponse;
    }

    private function determineFormat(HttpRequest $httpRequest)
    {
        $format = 'json';

        $requestHeaders = $httpRequest->getHeaders();
        if ($requestHeaders->has('accept')) {
            $acceptHeader = $requestHeaders->get('accept');
            foreach ($acceptHeader->getPrioritized() as $acceptFieldValuePart) {
                if ($acceptFieldValuePart->getFormat() === 'json' || $acceptFieldValuePart->getFormat() === 'xml') {
                    $format = $acceptFieldValuePart->getFormat();
                    break;
                }
            }
        }

        return $format;
    }

    public function decodeJson($json)
    {
        $json = trim($json);

        return json_decode($json, true);
    }
}
