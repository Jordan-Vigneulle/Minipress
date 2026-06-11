<?php
declare(strict_types=1);

namespace minipress\appli\webui\actions\Categories;

use minipress\appli\application_core\application\useCases\Users\AuthnService;
use minipress\appli\application_core\application\useCases\Users\AuthzService;
use minipress\appli\application_core\application\useCases\Users\AuthzServiceInterface;
use minipress\appli\application_core\domain\providers\AuthnProvider;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpForbiddenException;
use Slim\Exception\HttpUnauthorizedException;
use Slim\Views\Twig;

class CategorieCreateAction
{
    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $user = (new AuthnProvider(new AuthnService()))->getSignedInUser();

        if ($user === null) {
            throw new HttpUnauthorizedException($request);
        }

        try {
            (new AuthzService())->checkAuthorization($user, AuthzServiceInterface::CREATE_CATEGORY);
        } catch (\RuntimeException $e) {
            throw new HttpForbiddenException($request, $e->getMessage());
        }

        $twig = Twig::fromRequest($request);
        return $twig->render($response, 'Categories/CategorieCreateView.twig', [
            'csrf_token' => $_SESSION['csrf_categorie'] = bin2hex(random_bytes(32)),
        ]);
    }
}