<?php

declare(strict_types=1);

namespace gift\appli\webui\actions\Authentification;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\Twig;

final class AuthConnectionAction
{
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $queryParams = $request->getQueryParams();
        $params = [
            'title' => 'Connexion / Inscription',
            'error' => $queryParams['error'] ?? null,
            'register_error' => $queryParams['register_error'] ?? null,
            'success' => $queryParams['success'] ?? null,
            'old' => ['user_id' => $queryParams['old_user_id'] ?? ''],
            'csrf_token' => $_SESSION['csrf_login'] = bin2hex(random_bytes(32)),
            'csrf_token_register' => $_SESSION['csrf_register'] = bin2hex(random_bytes(32)),
        ];

        $twig = Twig::fromRequest($request);
        return $twig->render($response, 'Authentification/connectionView.twig', $params);
    }
}
