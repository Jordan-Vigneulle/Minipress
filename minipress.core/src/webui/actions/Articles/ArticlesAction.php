<?php
declare(strict_types=1);

namespace minipress\appli\webui\actions\Articles;

use minipress\appli\application_core\application\useCases\Articles\ArticleService;
use minipress\appli\application_core\application\useCases\Articles\ArticleServiceInterface;
use minipress\appli\application_core\application\useCases\Users\AuthnService;
use minipress\appli\application_core\application\useCases\Users\AuthzService;
use minipress\appli\application_core\application\useCases\Users\AuthzServiceInterface;
use minipress\appli\application_core\application\useCases\Users\UserService;
use minipress\appli\webui\providers\AuthnProvider;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;
use Slim\Exception\HttpUnauthorizedException;

class ArticlesAction
{
    private ArticleServiceInterface $articleService;

    public function __construct()
    {
        $this->articleService = new ArticleService();
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $user = (new AuthnProvider(new AuthnService()))->getSignedInUser();
        $userService = new UserService();
        if (!$user) {
            throw new HttpUnauthorizedException($request, "Accès refusé : utilisateur non authentifié");
        }

        $authz = new AuthzService();

        try {
            $authz->checkAuthorization($user, AuthzServiceInterface::VIEW_ALL_ARTICLES);
            $articles = $this->articleService->getArticles();
        } catch (\RuntimeException $e) {
            try {
                $authz->checkAuthorization($user, AuthzServiceInterface::VIEW_OWN_ARTICLES);
                $result = $userService->getArticlesByUser($user->id);
                $articles = $result['articles'];
            } catch (\RuntimeException $e) {
                throw new HttpUnauthorizedException($request, 'Accès refusé');
            }
        }



        $view = Twig::fromRequest($request);
        return $view->render($response, 'Articles/ArticlesView.twig', [
            'articles' => $articles
        ]);
    }
}
