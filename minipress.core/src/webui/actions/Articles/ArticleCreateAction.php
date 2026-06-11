<?php
declare(strict_types=1);

namespace minipress\appli\webui\actions\Articles;

use minipress\appli\application_core\application\useCases\categorie\CategorieService;
use minipress\appli\application_core\application\useCases\user\AuthnService;
use minipress\appli\application_core\application\useCases\user\AuthzService;
use minipress\appli\application_core\application\useCases\user\AuthzServiceInterface;
use minipress\appli\application_core\domain\providers\AuthnProvider;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpForbiddenException;
use Slim\Exception\HttpUnauthorizedException;
use Slim\Views\Twig;

class ArticleCreateAction
{
    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $user = (new AuthnProvider(new AuthnService()))->getSignedInUser();

        if ($user === null) {
            throw new HttpUnauthorizedException($request, 'Utilisateur non authentifié');
        }

        try {
            (new AuthzService())->checkAuthorization($user, AuthzServiceInterface::CREATE_ARTICLE);
        } catch (\RuntimeException $e) {
            throw new HttpForbiddenException($request, $e->getMessage());
        }

        $twig = Twig::fromRequest($request);
        return $twig->render($response, 'Articles/articleCreationTwig.twig', [
            'csrf_token' => $_SESSION['csrf_article'] = bin2hex(random_bytes(32)),
            'categories' => (new CategorieService())->getCategories(),
        ]);
    }
}