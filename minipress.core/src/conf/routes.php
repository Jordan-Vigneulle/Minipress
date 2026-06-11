<?php

declare(strict_types=1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use minipress\appli\webui\actions\HomeAction;
use minipress\appli\webui\actions\Articles\ArticleByIDAction;
use minipress\appli\webui\actions\Articles\ArticlesAction;
use minipress\appli\webui\actions\Articles\TogglePublishAction;
use minipress\appli\webui\actions\Articles\ArticleCreateAction;
use minipress\appli\webui\actions\Articles\ArticleStoreAction;

use minipress\appli\webui\actions\Categories\CategoriesAction;
use minipress\appli\webui\actions\Categories\ArticlesByCategorieAction;
use minipress\appli\webui\actions\Categories\CategorieParIDAction;
use minipress\appli\webui\actions\Categories\CategorieCreateAction;
use minipress\appli\webui\actions\Categories\CategorieStoreAction;

use minipress\appli\api\actions\ArticlesByCategorieAction as API_ArticlesByCategorieAction;
use minipress\appli\api\actions\CategoriesAction as API_CategoriesAction;
use minipress\appli\api\actions\ArticleByIDAction as API_ArticleByIDAction;
use minipress\appli\api\actions\ArticlesAction as API_ArticlesAction;
use minipress\appli\api\actions\ArticlesByUserAction as API_ArticlesByUserAction;

use minipress\appli\webui\actions\Authentification\AuthLoginPageAction;
use minipress\appli\webui\actions\Authentification\AuthSigninPageAction;
use minipress\appli\webui\actions\Authentification\AuthLoginAction;
use minipress\appli\webui\actions\Authentification\AuthSigninAction;
use minipress\appli\webui\actions\Authentification\AuthLogoutAction;
use minipress\appli\webui\actions\Profils\ProfilAction;
use minipress\appli\webui\actions\Profils\ProfilChangePasswordAction;
use minipress\appli\webui\actions\Profils\ProfilChangePseudoAction;
use minipress\appli\webui\actions\Profils\ProfilChangeAvatarAction;

use minipress\appli\webui\actions\Admin\AdminUsersListAction;

return function (\Slim\App $app): \Slim\App {

    $app->get('/', HomeAction::class)->setName('home');

    // Catégories
    $app->get('/categories', CategoriesAction::class)->setName('Categories');
    $app->get('/categories/{id}/articles', ArticlesByCategorieAction::class)->setName('ArticlesByCategorie');
    $app->get('/categories/create', CategorieCreateAction::class)->setName('CategorieCreate');
    $app->post('/categories/create', CategorieStoreAction::class)->setName('CategorieStore');

    // Articles
    $app->get('/articles', ArticlesAction::class)->setName('Articles');
    $app->post('/articles/{id}/toggle-publish', TogglePublishAction::class)->setName('TogglePublish');
    $app->get('/articles/create', ArticleCreateAction::class)->setName('ArticleCreate');
    $app->post('/articles/create', ArticleStoreAction::class)->setName('ArticleStore');
    $app->get('/articles/{id}', ArticleByIDAction::class)->setName('Article');

    // Authentification
    $app->get('/loginPage', AuthLoginPageAction::class)->setName('LoginPage');
    $app->post('/login', AuthLoginAction::class)->setName('Login');
    $app->get('/signinPage', AuthSigninPageAction::class)->setName('SigninPage');
    $app->post('/signin', AuthSigninAction::class)->setName('Signin');
    $app->get('/logout', AuthLogoutAction::class)->setName('Logout');

    // User
    $app->get('/usersList', AdminUsersListAction::class)->setName('usersList');

    // Profil
    $app->get('/profil', ProfilAction::class)->setName('profil');
    $app->post('/profil/avatar', ProfilChangeAvatarAction::class)->setName('ChangeAvatar');
    $app->post('/profil/password', ProfilChangePasswordAction::class)->setName('ChangePassword');
    $app->post('/profil/username', ProfilChangePseudoAction::class)->setName('ChangeUsername');

    // API
    $app->get('/api/categories', API_CategoriesAction::class)->setName('api_Categories');
    $app->get('/api/categories/{id}/articles', API_ArticlesByCategorieAction::class)->setName('api_ArticlesByCategorie');
    $app->get('/api/articles/{id}', API_ArticleByIDAction::class)->setName('api_Article');
    $app->get('/api/articles', API_ArticlesAction::class)->setName('api_ListeArticles');
    $app->get('/api/auteurs/{id}/articles', API_ArticlesByUserAction::class)->setName('api_AllArticlesByAuteur');

    return $app;
};