<?php

declare(strict_types=1);

namespace minipress\appli\webui\actions\Categories;

use minipress\appli\application_core\application\useCases\Categories\CategorieService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

class CategoriesAction
{
    public function __invoke(
        Request $request,
        Response $response,
        array $args
    ): Response {

        $service = new CategorieService();
        $categories = $service->getCategoriesWithPublishedArticles();

        $twig = Twig::fromRequest($request);
        return $twig->render($response, 'Categories/CategoriesView.twig', [
            'categories' => $categories,
        ]);
    }
}