<?php

declare(strict_types=1);

namespace minipress\appli\webui\actions\Articles;

use minipress\appli\application_core\application\useCases\Articles\ArticleService;
use minipress\appli\application_core\application\useCases\Users\AuthnService;
use minipress\appli\application_core\application\useCases\Users\AuthzService;
use minipress\appli\application_core\application\useCases\Users\AuthzServiceInterface;
use minipress\appli\application_core\application\useCases\Users\UserService;
use minipress\appli\webui\providers\AuthnProvider;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;
use Slim\Exception\HttpUnauthorizedException;

class ArticleByIDAction
{
    public function __invoke(
        Request $request,
        Response $response,
        array $args
    ): Response {

        $id = $args['id'] ?? null;
        if (!$id) {
            throw new \Slim\Exception\HttpBadRequestException($request, "id manquant");
        }

        $user = (new AuthnProvider(new AuthnService()))->getSignedInUser();
        if (!$user) {
            throw new HttpUnauthorizedException($request, "Accès refusé : utilisateur non authentifié");
        }

        $authz = new AuthzService();
        $service = new ArticleService();
        $userService = new UserService();

        try {
            $authz->checkAuthorization($user, AuthzServiceInterface::VIEW_ALL_ARTICLES);
            $article = $service->getArticleById((int)$id);
        } catch (\RuntimeException $e) {
            try {
                $authz->checkAuthorization($user, AuthzServiceInterface::VIEW_OWN_ARTICLES);
                $result = $userService->getArticlesByUser($user->id);
                $userArticleIds = array_column($result['articles'], 'id');
                if (!in_array((int)$id, $userArticleIds)) {
                    throw new HttpUnauthorizedException($request, "Accès refusé : cet article ne vous appartient pas");
                }
                $article = $service->getArticleById((int)$id);
            } catch (HttpUnauthorizedException $e) {
                throw $e;
            } catch (\RuntimeException $e) {
                throw new HttpUnauthorizedException($request, 'Accès refusé');
            }
        }

        try {
            $dateTime = new \DateTime($article['date']);
            $date = $dateTime->format('d/m/Y H:i');
            $contenuHTML = $service->markdownToHTML($article['contenu']);
        } catch (\Exception $e) {
            throw new \Slim\Exception\HttpNotFoundException($request, $e->getMessage());
        }

        $twig = Twig::fromRequest($request);
        return $twig->render($response, 'Articles/ArticleByIDView.twig', [
            'article' => $article,
            'date' => $date,
            'contenuHTML' => $contenuHTML,
        ]);
    }
}
