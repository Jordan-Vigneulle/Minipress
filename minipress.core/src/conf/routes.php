<?php

declare(strict_types=1);

use minipress\appli\webui\actions\AccueilAction;
use minipress\appli\webui\actions\Articles\ListArticlesAction;
use minipress\appli\webui\actions\Articles\TogglePublishAction;

use minipress\appli\webui\actions\Article\ArticleCreateAction;
use minipress\appli\webui\actions\Article\ArticleStoreAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use minipress\appli\webui\actions\Categories\CategoriesListeAction;
use minipress\appli\webui\actions\Categories\ArticlesParCategorieAction;
use minipress\appli\webui\actions\Categories\ArticleParIDAction;

use minipress\appli\api\actions\ArticlesParCategorieAction as API_ArticlesParCategorieAction;
use minipress\appli\api\actions\CategoriesAction;
use minipress\appli\api\actions\ArticleParIDAction as API_ArticleParIDAction;

return function (\Slim\App $app): \Slim\App {

    $app->get('/', AccueilAction::class)->setName('home');

    // Catégories
    $app->get('/categories', CategoriesListeAction::class)->setName('allCategories');
    $app->get('/categories/{id}/articles', ArticlesParCategorieAction::class)->setName('allArticlesByCategorie');

    // Articles
    $app->get('/articles/{id}', ArticleParIDAction::class)->setName('oneArticle');
    $app->get('/articles', ListArticlesAction::class)->setName('liste_articles');
    $app->post('/articles/{id}/toggle-publish', TogglePublishAction::class)->setName('toggle_publish');

    // API
    $app->get('/api/categories', CategoriesAction::class)->setName('api_AllCategories');
    $app->get('/api/categories/{id}/articles', API_ArticlesParCategorieAction::class)->setName('api_AllArticlesByCategorie');
    $app->get('/api/articles/{id}', API_ArticleParIDAction::class)->setName('api_OneArticle');
   
    $app->get('/article/create', ArticleCreateAction::class)->setName('articleCreate');
    $app->post('/article/create', ArticleStoreAction::class)->setName('articleStore');

    return $app;
};