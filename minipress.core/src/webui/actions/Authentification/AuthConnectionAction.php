<?php

declare(strict_types=1);

namespace minipress\appli\webui\actions\Authentification;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\Twig;

final class AuthConnectionAction
{
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $queryParams = $request->getQueryParams();
        $params = [
            'title' => 'Connexion',
            'error' => $queryParams['error'] ?? null,
            'success' => $queryParams['success'] ?? null,
            'old' => ['email' => $queryParams['old_email'] ?? ''],
            'csrf_token' => $_SESSION['csrf_token'] = bin2hex(random_bytes(32)),
        ];
        $twig = Twig::fromRequest($request);
        return $twig->render($response, 'Authentification/connectionView.twig', $params);
    }
}
