<?php
declare(strict_types=1);

namespace minipress\appli\webui\actions\Article;


use minipress\appli\application_core\application\useCases\categorie\GestionCategorie;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Flash\Messages;
use Slim\Views\Twig;

class ArticleCreateAction
{

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $twig = Twig::fromRequest($request);
        return $twig->render($response, 'Article/articleCreationTwig.twig', [
            'csrf_token' => $_SESSION['csrf_article'] = bin2hex(random_bytes(32)),
            'categories' => GestionCategorie::getCategories(),
        ]);
    }
}