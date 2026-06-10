<?php

declare(strict_types=1);

namespace minipress\appli\webui\actions\Categories;

use minipress\appli\application_core\application\useCases\categorie\GestionCategorie;
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
            $service = new GestionCategorie();

            $article = $service->getArticleById((int)$id);
        } catch (\Exception $e) {
            throw new \Slim\Exception\HttpNotFoundException($request, $e->getMessage());
        }

        $date = new \DateTime($article['date'])->format('d/m/Y H:i');

        $twig = Twig::fromRequest($request);
        return $twig->render($response, 'Categories/ArticleParIDView.twig', [
            'article' => $article,
            'date' => $date,
        ]);
    }
}