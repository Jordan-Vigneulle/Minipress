<?php

declare(strict_types=1);

namespace minipress\appli\api\actions;

use minipress\appli\application_core\application\useCases\categorie\CategorieService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class CategoriesAction
{
    public function __invoke(
        Request $request,
        Response $response,
        array $args
    ): Response {

        $service = new CategorieService();
        $categories = $service->getCategories();
        
        $response->getBody()->write(json_encode($categories));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }
}