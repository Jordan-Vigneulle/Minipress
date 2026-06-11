<?php
declare(strict_types=1);

namespace minipress\appli\webui\actions\Authentification;

use minipress\appli\application_core\application\useCases\user\AuthnService;
use minipress\appli\webui\providers\AuthnProvider;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteContext;
use Slim\Exception\HttpForbiddenException;
use Slim\Flash\Messages;

class AuthLoginAction
{
    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $routeParser = RouteContext::fromRequest($request)->getRouteParser();
        $provider = new AuthnProvider(new AuthnService());
        $flash = new Messages();

        $data = $request->getParsedBody();
        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';

        if (!hash_equals($_SESSION['csrf_token'] ?? '', $data['csrf_token'] ?? '')) {
            throw new HttpForbiddenException($request);
        }
        unset($_SESSION['csrf_token']);

        if (empty($email) || empty($data['password'] ?? '')) {
            $flash->addMessage('error', 'Veuillez saisir l\'identifiant et le mot de passe');

            return $response->withHeader(
                'Location',
                $routeParser->urlFor('loginPage')
            )->withStatus(302);
        }

        $user = $provider->signin($email, $data['password']);

        if ($user === null) {
            $flash->addMessage('error', 'Identifiants incorrects');

            return $response->withHeader(
                'Location',
                $routeParser->urlFor('loginPage')
            )->withStatus(302);
        }

        return $response->withHeader(
            'Location',
            $routeParser->urlFor('home')
        )->withStatus(302);
    }
}