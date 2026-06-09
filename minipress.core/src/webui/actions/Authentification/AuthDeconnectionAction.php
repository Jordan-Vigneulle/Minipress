<?php

namespace minipress\appli\webui\actions\Authentification;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteContext;

class AuthDeconnectionAction {
    public function __invoke(Request $request, Response $response, array $args): Response {
        $routeParser = RouteContext::fromRequest($request)->getRouteParser();
        session_destroy();
        return $response->withHeader('Location',$routeParser->urlFor('home'))->withStatus(302);
    }
}