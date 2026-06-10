<?php

declare(strict_types=1);


use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use minipress\appli\webui\actions\AccueilAction;
use minipress\appli\webui\actions\Categories\CategoriesListeAction;
use minipress\appli\webui\actions\Categories\ArticlesParCategorieAction;
use minipress\appli\webui\actions\Categories\ArticleParIDAction;

return function (\Slim\App $app): \Slim\App {

    $app->get('/', AccueilAction::class)->setName('home');

    // Catégories
    $app->get('/categories', CategoriesListeAction::class)->setName('allCategories');
    $app->get('/categories/{id}/articles', ArticlesParCategorieAction::class)->setName('allArticlesByCategorie');

    $app->get('/articles/{id}', ArticleParIDAction::class)->setName('oneArticle');
   
    return $app;
};