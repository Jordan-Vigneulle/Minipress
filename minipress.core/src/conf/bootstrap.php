<?php
declare(strict_types=1);

session_start();

use gift\appli\Infrastructure\Eloquent;
use gift\appli\application_core\domain\entities\User;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;
use Slim\Flash\Messages;

require_once __DIR__ . '/../vendor/autoload.php';

Eloquent::init(__DIR__ . '/database.ini');

$app = \Slim\Factory\AppFactory::create();
$app->setBasePath('');
$app->addRoutingMiddleware();
$app = (require_once __DIR__ . '/../conf/routes.php')($app);

return $app;