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
use minipress\appli\application_core\application\useCases\user\AuthzService;
use minipress\appli\application_core\application\useCases\user\AuthzServiceInterface;

require_once __DIR__ . '/../vendor/autoload.php';

Eloquent::init(__DIR__ . '/database.ini');

$app = \Slim\Factory\AppFactory::create();
$app->setBasePath('');
$twig = Twig::create(__DIR__ . '/../webui/views', ['cache' => false]);
$app->add(TwigMiddleware::create($app, $twig));
$app->addRoutingMiddleware();
$app = (require_once __DIR__ . '/../conf/routes.php')($app);

// Flash
$flash = new Messages();
$twig->getEnvironment()->addGlobal('flash', $flash->getMessages());


//CSS+JS
$twig->getEnvironment()->addGlobal('css_dir', '/css');
$twig->getEnvironment()->addGlobal('js_dir', '/js');

$navItems = [
    ['url' => 'home', 'label' => 'Accueil'],
];

if (!empty($_SESSION['user'])) {
    $navItems[] = ['url' => 'liste_articles', 'label' => 'Gérer les articles'];
    $navItems[] = ['url' => 'articleCreate', 'label' => 'Créer un article'];
    $navItems[] = ['url' => 'formCreateCategorie', 'label' => 'Créer une catégorie'];
    $u = Utilisateur::find($_SESSION['user']);
    if ($u && $u->role == 100) { // Check pour admin
        $navItems[] = ['url' => 'createUserPage', 'label' => 'Créer un utilisateur'];
    }
}
$twig->getEnvironment()->addGlobal('nav_items', $navItems);
// Utilisateur connecte + Avatar
$twig->getEnvironment()->addGlobal('estConnecte', !empty($_SESSION['user']));

$avatarPath = '/images/avatars/avatar_default.png';
if (!empty($_SESSION['user'])) {
    $u = Utilisateur::find($_SESSION['user']);
    $avatarPath = $u?->chemin_acces_img ?: '/images/avatars/avatar_default.png';
}

$twig->getEnvironment()->addGlobal('avatar_utilisateur_path', $avatarPath);


$errorMiddleware = $app->addErrorMiddleware(true, true, true);
$errorMiddleware->setDefaultErrorHandler(function (Request $request, \Throwable $exception, bool $displayErrorDetails) use ($app, $twig) {
    $code = 500;
    if ($exception instanceof \Slim\Exception\HttpNotFoundException) {
        $code = 404;
    } elseif ($exception instanceof \Slim\Exception\HttpForbiddenException) {
        $code = 403;
    } elseif ($exception instanceof \Slim\Exception\HttpUnauthorizedException) {
        $code = 401;
    }

    $response = $app->getResponseFactory()->createResponse();
    return $twig->render($response->withStatus($code), 'erreurView.twig', [
        'code' => $code,
        'message' => $exception->getMessage(),
    ]);
});
return $app;