<?php

declare(strict_types=1);

namespace minipress\appli\api\actions;

use minipress\appli\application_core\application\useCases\Users\UserService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class UsersAction
{
    public function __invoke(
        Request $request,
        Response $response,
        array $args
    ): Response {

        $service = new UserService();
        $users = $service->getUsers();
        
        $response->getBody()->write(json_encode($users));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }
}