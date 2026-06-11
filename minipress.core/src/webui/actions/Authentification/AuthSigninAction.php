<?php
declare(strict_types=1);

namespace minipress\appli\webui\actions\Authentification;

use minipress\appli\application_core\application\useCases\Users\AuthnService;
use minipress\appli\application_core\application\useCases\Users\AuthnServiceInterface;
use minipress\appli\application_core\application\useCases\Users\AuthzService;
use minipress\appli\application_core\application\useCases\Users\AuthzServiceInterface;
use minipress\appli\application_core\domain\providers\AuthnProvider;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpUnauthorizedException;

use Slim\Flash\Messages;
use Slim\Routing\RouteContext;

class AuthSigninAction
{
    private AuthnServiceInterface $authnService;

    public function __construct()
    {
        $this->authnService = new AuthnService();
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $routeParser = RouteContext::fromRequest($request)->getRouteParser();
        $flash = new Messages();

        $authnProvider = new AuthnProvider($this->authnService);
        $currentUser = $authnProvider->getSignedInUser();

        if ($currentUser === null) {
            throw new HttpUnauthorizedException($request, "Accès refusé : utilisateur non authentifié");
        }
        $data = $request->getParsedBody();
        $email = trim($data['email'] ?? '');

        if (!hash_equals($_SESSION['csrf_token'] ?? '', $data['csrf_token'] ?? '')) {
            throw new HttpForbiddenException($request);
        }
        unset($_SESSION['csrf_token']);

        try {
            (new AuthzService())->checkAuthorization($currentUser, AuthzServiceInterface::CREATE_USER);
        } catch (\RuntimeException $e) {
            throw new HttpForbiddenException($request, 'Réservé à l\'admin');
        }

        try {
            $this->authnService->register($email, $data['password'] ?? '');
            $flash->addMessage('success', 'Utilisateur créé avec succès');
        } catch (\RuntimeException $e) {
            $flash->addMessage('error', $e->getMessage());

            if ($email !== '') {
                $flash->addMessage('old_email', $email);
            }

            return $response->withHeader(
                'Location',
                $routeParser->urlFor('SigninPage')
            )->withStatus(302);
        }

        return $response->withHeader(
            'Location',
            $routeParser->urlFor('SigninPage')
        )->withStatus(302);
    }
}