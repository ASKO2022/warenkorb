<?php

namespace Cart\Controllers;

use Cart\Services\RegisterService;
use Psr\Container\ContainerInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class RegisterController
{
    protected $container;
    protected $registerService;

    public function __construct(ContainerInterface $container, RegisterService $registerService)
    {
        $this->container = $container;
        $this->registerService = $registerService;
    }

    public function showRegisterForm(Request $request, Response $response, $args)
    {
        $view = $this->container->get('view');
        return $view->render($response, 'register.html.twig');
    }

    public function register(Request $request, Response $response, $args)
    {
        $data = $request->getParsedBody();
        $username = $data['username'];
        $email = $data['email'];
        $password = $data['password'];
        $confirmPassword = $data['confirm_password'];

        if ($password !== $confirmPassword) {
            return $response->withHeader('Location', '/register?error=passwords_not_match')->withStatus(302);
        }

        $result = $this->registerService->register($username, $email, $password);

        if ($result === "Registrierung erfolgreich!") {
            $_SESSION['user'] = $username;
            return $response->withHeader('Location', '/products')->withStatus(302);
        }

        return $response->withHeader('Location', '/register?error=registration_failed')->withStatus(302);

    }
}


