<?php

declare(strict_types=1);

namespace minipress\appli\webui\actions\Categories;

use minipress\appli\application_core\application\useCases\categorie\CategorieService;
use minipress\appli\application_core\application\useCases\article\ArticleService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

class ArticlesParCategorieAction
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
            $serviceCat = new CategorieService();
            $serviceArt = new ArticleService();

            $data = $serviceCat->getArticlesByCategorie((int)$id);
            $categorie = $data['categorie'];
            $articles = $data['articles'];

            $resumes = [];
            foreach ($articles as $article) {
                $resumes[$article['id']] = $serviceArt->markdownToHTML($article['resume']);
            }

        } catch (\Exception $e) {
            throw new \Slim\Exception\HttpNotFoundException($request, $e->getMessage());
        }
        
      

        $twig = Twig::fromRequest($request);
        return $twig->render($response, 'Categories/ArticlesParCategorieView.twig', [
            'categorie' => $categorie,
            'articles' => $articles,
            'resumes' => $resumes
        ]);
    }
}