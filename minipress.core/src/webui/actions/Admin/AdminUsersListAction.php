<?php
declare(strict_types=1);

namespace minipress\appli\webui\actions\Admin;

use minipress\appli\application_core\application\useCases\Users\AuthnService;
use minipress\appli\application_core\application\useCases\Users\AuthzService;
use minipress\appli\application_core\application\useCases\Users\AuthzServiceInterface;
use minipress\appli\application_core\domain\entities\Utilisateur;
use minipress\appli\webui\providers\AuthnProvider;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpForbiddenException;
use Slim\Exception\HttpUnauthorizedException;
use Slim\Views\Twig;

class AdminUsersListAction
{
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $user = (new AuthnProvider(new AuthnService()))->getSignedInUser();

        if (!$user) {
            throw new HttpUnauthorizedException($request, 'Accès refusé : utilisateur non authentifié');
        }

        try {
            (new AuthzService())->checkAuthorization($user, AuthzServiceInterface::CREATE_USER); //Même droit donc j'utilise CREATE_USER
        } catch (\RuntimeException $e) {
            throw new HttpForbiddenException($request, $e->getMessage());
        }

        $utilisateurs = Utilisateur::orderBy('id', 'asc')->get()->all();

        $twig = Twig::fromRequest($request);
        return $twig->render($response, 'Admin/UsersView.twig', [
            'utilisateurs' => $utilisateurs,
        ]);
    }
}