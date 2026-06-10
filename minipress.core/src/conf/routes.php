<?php

declare(strict_types=1);

use minipress\appli\webui\actions\AccueilAction;
use minipress\appli\webui\actions\Articles\ListArticlesAction;
use minipress\appli\webui\actions\Articles\TogglePublishAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

return function (\Slim\App $app): \Slim\App {

    $app->get('/', AccueilAction::class)->setName('home');

    $app->get('/admin/articles', ListArticlesAction::class)->setName('admin_liste_articles');
    $app->post('/admin/articles/{id}/toggle-publish', TogglePublishAction::class)->setName('admin_toggle_publish');

    return $app;
};