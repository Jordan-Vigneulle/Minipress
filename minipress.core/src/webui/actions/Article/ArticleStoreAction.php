<?php
declare(strict_types=1);

namespace minipress\appli\webui\actions\Article;


use minipress\appli\application_core\application\useCases\article\create\ArticleCreate;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteContext;
use Slim\Views\Twig;

class ArticleStoreAction
{
    private ArticleCreate $articleCreation;

    public function __construct()
    {
        $this->articleCreation = new ArticleCreate();
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $routeParser = RouteContext::fromRequest($request)->getRouteParser();
        $data = $request->getParsedBody() ?? [];
        $errors = [];

        $titre = trim($data['titre'] ?? '');
        $resume = trim($data['resume'] ?? '');
        $contenu = $data['contenu'] ?? '0.00';

        if ($titre === '') {
            $errors['titre'] = 'Le Titre est obligatoire.';
        }
        if ($contenu === '') {
            $errors['contenu'] = 'Merci de saisir un contenu.';
        }
        var_dump("eeee");
        if (!empty($errors)) {
            $twig = Twig::fromRequest($request);
            return $twig->render($response->withStatus(422), 'Box/articleCreationView.twig', [
                'errors' => $errors,
                'old' => $data,
                'csrf_token' => bin2hex(random_bytes(32)),
            ]);
        }

        try {
            $this->articleCreation->create($titre, $resume, $contenu);

        } catch (\Exception $e) {
            $flash = new \Slim\Flash\Messages();
            $flash->addMessage('error', 'Une erreur est survenue, veuillez réessayer.');
            var_dump($e->getMessage());
            return $response->withHeader('Location', $routeParser->urlFor('articleCreate'))->withStatus(302);
        }
        return $response->withHeader('Location', $routeParser->urlFor('home'))->withStatus(302);
    }
}