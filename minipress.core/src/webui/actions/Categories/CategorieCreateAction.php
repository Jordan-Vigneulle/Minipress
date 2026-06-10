<?php

declare(strict_types=1);

namespace minipress\appli\webui\actions\Categories;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

class CategorieCreateAction
{
    public function __invoke(
        Request $request,
        Response $response,
        array $args
    ): Response {
        
        $twig = Twig::fromRequest($request);
        return $twig->render($response, 'Categories/CategorieForm.twig');
    }
}