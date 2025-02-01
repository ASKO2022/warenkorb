<?php

namespace Cart\Controllers;


use Slim\Psr7\Request;
use Slim\Psr7\Response;

class LogoutController
{
    public function __construct()
    {

    }

    public function logout(Request $request, Response $response, $args)
    {
        session_unset();
        session_destroy();

        return $response->withHeader('Location', '/login')->withStatus(302);
    }
}