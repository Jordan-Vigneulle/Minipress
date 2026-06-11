<?php
declare(strict_types=1);

session_start();

use minipress\appli\Infrastructure\Eloquent;
use minipress\appli\application_core\domain\entities\Utilisateur;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;
use Slim\Flash\Messages;

require_once __DIR__ . '/../vendor/autoload.php';

Eloquent::init(__DIR__ . '/database.ini');

$app = \Slim\Factory\AppFactory::create();
$app->setBasePath('');
$twig = Twig::create(__DIR__ . '/../webui/views', ['cache' => false]);
$app->add(TwigMiddleware::create($app, $twig));
$app->addRoutingMiddleware();
$app = (require_once __DIR__ . '/../conf/routes.php')($app);

$navItems = [
    ['url' => 'articleCreate', 'label' => 'Création d\'article'],
    ['url' => 'allCategories', 'label' => 'Catégories'],
    ['url' => 'formCreateCategorie', 'label' => 'Création de catégorie'],
];

// Flash
$flash = new Messages();
$twig->getEnvironment()->addGlobal('flash', $flash->getMessages());
$twig->getEnvironment()->addGlobal('css_dir', '/css');
$twig->getEnvironment()->addGlobal('nav_items', $navItems);

return $app;