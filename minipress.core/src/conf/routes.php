<?php

declare(strict_types=1);

use minipress\appli\webui\actions\AccueilAction;
use minipress\appli\webui\actions\Article\ArticleCreateAction;
use minipress\appli\webui\actions\Article\ArticleStoreAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

return function (\Slim\App $app): \Slim\App {

    $app->get('/', AccueilAction::class)->setName('home');

    $app->get('/article/create', ArticleCreateAction::class)->setName('articleCreate');
    $app->post('/article/create', ArticleStoreAction::class)->setName('articleStore');

    return $app;
};