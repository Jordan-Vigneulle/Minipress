<?php
namespace minipress\appli\webui\actions\Profils;

use minipress\appli\application_core\application\useCases\user\AuthnService;
use minipress\appli\application_core\application\useCases\user\UserService;
use minipress\appli\webui\providers\AuthnProvider;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteContext;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Exception\HttpUnauthorizedException;

class ProfilsChangementAvatarAction
{
    // Liste des avatars disponibles dans la bibliothèque
    public const AVATARS = [
        '/images/avatars/Nathan.png',
        '/images/avatars/Thibaut.png',
        '/images/avatars/Maryam.png',
        '/images/avatars/Jordan.png',
        '/images/avatars/Auriane.png',
        '/images/avatars/Tidus.png',
        '/images/avatars/Yuna.png',
        '/images/avatars/Tidus-Yuna.png',
        '/images/avatars/Cloud.png',
        '/images/avatars/Aerith.png',
        '/images/avatars/Aerith-Cloud.png',
        '/images/avatars/ChatTropCool1.png',
        '/images/avatars/ChatTropCool2.png',
        '/images/avatars/ChatTropCool3.png',
        '/images/avatars/Chi.png',
        // Ajoutez d'autres avatars ici si besoin. Pour le groupe !
    ];

    public function __invoke(Request $request, Response $response): Response
    {
        $routeParser = RouteContext::fromRequest($request)->getRouteParser();
        $user = (new AuthnProvider(new AuthnService()))->getSignedInUser();

        if (!$user) {
            throw new HttpUnauthorizedException($request, "Accès refusé : utilisateur non authentifié");
        }

        $data = $request->getParsedBody();
        $chosen = $data['avatar_path'] ?? '';

        // Vérifie que le chemin soumis fait partie de la liste autorisée
        if (!in_array($chosen, self::AVATARS, true)) {
            throw new HttpBadRequestException($request, "Avatar invalide");
        }

        try {
            (new UserService())->changeAvatar($user, $chosen);
        } catch (\RuntimeException $e) {
            throw new HttpInternalServerErrorException($request, "Erreur lors de la mise à jour de l'avatar");
        }

        return $response->withHeader(
            'Location',
            $routeParser->urlFor('profil', [], ['success' => 'Photo de profil mise à jour'])
        )->withStatus(302);
    }
}