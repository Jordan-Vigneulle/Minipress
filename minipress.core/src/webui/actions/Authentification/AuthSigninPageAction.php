<?php
declare(strict_types=1);

namespace minipress\appli\webui\actions\Authentification;

use minipress\appli\application_core\application\useCases\Users\AuthnService;
use minipress\appli\application_core\application\useCases\Users\AuthzService;
use minipress\appli\application_core\application\useCases\Users\AuthzServiceInterface;
use minipress\appli\application_core\domain\providers\AuthnProvider;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpForbiddenException;
use Slim\Views\Twig;

final class AuthSigninPageAction
{
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $user = (new AuthnProvider(new AuthnService()))->getSignedInUser();

        if (!$user) {
            throw new HttpForbiddenException($request, 'Accès refusé');
        }

        try {
            (new AuthzService())->checkAuthorization($user, AuthzServiceInterface::CREATE_USER);
        } catch (\RuntimeException $e) {
            throw new HttpForbiddenException($request, $e->getMessage());
        }

        $params = [
            'title' => 'Créer un utilisateur',
            'csrf_token' => $_SESSION['csrf_token'] = bin2hex(random_bytes(32)),
        ];

        $twig = Twig::fromRequest($request);
        return $twig->render($response, 'Authentification/SigninView.twig', $params);
    }
}