<?php

namespace Cart\Controllers;

use Cart\Services\LoginService;
use Psr\Container\ContainerInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class LoginController
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function showLoginForm(Request $request, Response $response, $args)
    {
        $user = $_SESSION['user'] ?? null;

        $view = $this->container->get('view');
        return $view->render($response, 'login.html.twig', [
            'user' => $user,
            'errorMessage' => null,
        ]);
    }

    public function login(Request $request, Response $response)
    {
        $data = $request->getParsedBody();
        $username = $data['username'] ?? '';
        $password = $data['password'] ?? '';

        $loginService = $this->container->get(LoginService::class);
        $loginResult = $loginService->login($username, $password);

        if (is_object($loginResult)) {
            $_SESSION['user'] = $loginResult;

            return $response->withHeader('Location', '/login')->withStatus(302);
        }

        $view = $this->container->get('view');
        return $view->render($response, 'login.html.twig', [
            'user' => null,
            'errorMessage' => $loginResult,
        ]);
    }
}

