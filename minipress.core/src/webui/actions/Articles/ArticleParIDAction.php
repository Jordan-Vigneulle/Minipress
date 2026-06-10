<?php

declare(strict_types=1);

namespace minipress\appli\webui\actions\Articles;

use minipress\appli\application_core\application\useCases\article\ArticleService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

class ArticleParIDAction
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

       try {
            $service = new ArticleService();
            $article = $service->getArticleById((int)$id);

            $date = new \DateTime($article['date'])->format('d/m/Y H:i');
            $contenuHTML = $service->markdownToHTML($article['contenu']);
        } catch (\Exception $e) {
            throw new \Slim\Exception\HttpNotFoundException($request, $e->getMessage());
        }

        $twig = Twig::fromRequest($request);
        return $twig->render($response, 'articles/ArticleParIDView.twig', [
            'article' => $article,
            'date' => $date,
            'contenuHTML' => $contenuHTML,
        ]);
    }
}