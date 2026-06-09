<?php

declare(strict_types=1);

use minipress\appli\webui\actions\AccueilAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

return function (\Slim\App $app): \Slim\App {

    $app->get('/', AccueilAction::class)->setName('home');

   
    return $app;
};