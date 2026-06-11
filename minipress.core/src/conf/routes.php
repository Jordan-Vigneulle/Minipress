<?php

declare(strict_types=1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use minipress\appli\webui\actions\AccueilAction;
use minipress\appli\webui\actions\Articles\ArticleParIDAction;
use minipress\appli\webui\actions\Articles\ListArticlesAction;
use minipress\appli\webui\actions\Articles\TogglePublishAction;
use minipress\appli\webui\actions\Articles\ArticleCreateAction;
use minipress\appli\webui\actions\Articles\ArticleStoreAction;

use minipress\appli\webui\actions\Categories\CategoriesListeAction;
use minipress\appli\webui\actions\Categories\ArticlesParCategorieAction;
use minipress\appli\webui\actions\Categories\CategorieParIDAction;
use minipress\appli\webui\actions\Categories\CategorieCreateAction;
use minipress\appli\webui\actions\Categories\CategorieStoreAction;

use minipress\appli\api\actions\ArticlesParCategorieAction as API_ArticlesParCategorieAction;
use minipress\appli\api\actions\CategoriesAction;
use minipress\appli\api\actions\ArticleParIDAction as API_ArticleParIDAction;
use minipress\appli\api\actions\ArticlesAction as API_ListArticlesAction;
use minipress\appli\api\actions\ArticlesParAuteurAction as API_ArticlesParAuteurAction;

use minipress\appli\webui\actions\Authentification\AuthConnectionAction;
use minipress\appli\webui\actions\Authentification\AuthInscriptionAction;
use minipress\appli\webui\actions\Authentification\AuthLoginAction;
use minipress\appli\webui\actions\Authentification\AuthRegisterAction;
use minipress\appli\webui\actions\Authentification\AuthDeconnectionAction;
use minipress\appli\webui\actions\Profils\ProfileAction;
use minipress\appli\webui\actions\Profils\ProfilsChangementMDPAction;
use minipress\appli\webui\actions\Profils\ProfilsChangementPseudoAction;
use minipress\appli\webui\actions\Profils\ProfilsChangementAvatarAction;

return function (\Slim\App $app): \Slim\App {

    $app->get('/', AccueilAction::class)->setName('home');

    // Catégories
    $app->get('/categories', CategoriesListeAction::class)->setName('allCategories');
    $app->get('/categories/{id}/articles', ArticlesParCategorieAction::class)->setName('allArticlesByCategorie');

    $app->get('/categories/create', CategorieCreateAction::class)->setName('formCreateCategorie');
    $app->post('/categories/create', CategorieStoreAction::class)->setName('createCategorie');

    // Articles
    $app->get('/articles', ListArticlesAction::class)->setName('liste_articles');
    $app->post('/articles/{id}/toggle-publish', TogglePublishAction::class)->setName('toggle_publish');
    $app->get('/articles/create', ArticleCreateAction::class)->setName('articleCreate');
    $app->post('/articles/create', ArticleStoreAction::class)->setName('articleStore');
    $app->get('/articles/{id}', ArticleParIDAction::class)->setName('oneArticle');

    // API
    $app->get('/api/categories', CategoriesAction::class)->setName('api_AllCategories');
    $app->get('/api/categories/{id}/articles', API_ArticlesParCategorieAction::class)->setName('api_AllArticlesByCategorie');
    $app->get('/api/articles/{id}', API_ArticleParIDAction::class)->setName('api_OneArticle');
    $app->get('/api/articles', API_ListArticlesAction::class)->setName('api_ListeArticles');
    $app->get('/api/auteurs/{id}/articles', API_ArticlesParAuteurAction::class)->setName('api_AllArticlesByAuteur');

    // Authentification
    $app->get('/pageLogin', AuthConnectionAction::class)->setName('loginPage');
    $app->post('/login', AuthLoginAction::class)->setName('login');
    $app->get('/pageCreateUser', AuthInscriptionAction::class)->setName('createUserPage');
    $app->post('/register', AuthRegisterAction::class)->setName('register');
    $app->get('/logout', AuthDeconnectionAction::class)->setName('logout');

    // Profil
    $app->get('/profil', ProfileAction::class)->setName('profil');
    $app->post('/profil/avatar', ProfilsChangementAvatarAction::class)->setName('changeAvatar');
    $app->post('/profil/password', ProfilsChangementMDPAction::class)->setName('changePassword');
    $app->post('/profil/username', ProfilsChangementPseudoAction::class)->setName('changeUsername');

    return $app;
};