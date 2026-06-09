<?php
declare(strict_types=1);

namespace gift\appli\webui\actions\Authentification;

use gift\appli\application_core\application\useCases\user\AuthnService;
use gift\appli\application_core\application\useCases\user\AuthnServiceInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Routing\RouteContext;
use Slim\Exception\HttpForbiddenException;
use Slim\Flash\Messages; // Ajout du gestionnaire Flash

class AuthRegisterAction
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

        $data = $request->getParsedBody();
        $userId = $data['user_id'] ?? '';

        if (!hash_equals($_SESSION['csrf_register'] ?? '', $data['csrf_token'] ?? '')) {
            throw new HttpForbiddenException($request);
        }
        unset($_SESSION['csrf_register']);

        try {
            $this->authnService->register($userId, $data['password'] ?? '');
        } catch (\RuntimeException $e) {
            $flash->addMessage('error', $e->getMessage());

            if ($userId !== '') {
                $flash->addMessage('old_user_id', $userId);
            }

            return $response->withHeader(
                'Location',
                $routeParser->urlFor('loginPage')
            )->withStatus(302);
        }

        $flash->addMessage('success', 'Inscription réussie, veuillez vous connecter');

        return $response->withHeader(
            'Location',
            $routeParser->urlFor('loginPage')
        )->withStatus(302);
    }
}