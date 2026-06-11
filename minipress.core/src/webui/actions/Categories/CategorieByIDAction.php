<?php

declare(strict_types=1);

namespace minipress\appli\webui\actions\Categories;

use minipress\appli\application_core\application\useCases\Categories\CategorieService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

class CategorieParIDAction
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
            $service = new CategorieService();

            $categorie = $service->getCategorieById((int)$id);
        } catch (\Exception $e) {
            throw new \Slim\Exception\HttpNotFoundException($request, $e->getMessage());
        }

        $twig = Twig::fromRequest($request);
        return $twig->render($response, 'Categories/CategorieByIDView.twig', [
            'categorie' => $categorie,
        ]);
    }
}