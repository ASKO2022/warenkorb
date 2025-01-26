<?php

namespace Cart\Controllers;

use Cart\Models\Customer;
use Psr\Container\ContainerInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class RegisterController
{

    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
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

        $customer = new Customer();

        if ($password !== $confirmPassword) {
            return $response->withHeader('Location', '/register?error=passwords_not_match')->withStatus(302);
        }

        $result = $customer->register($username, $email, $password);

        if ($result === "Registrierung erfolgreich!") {
            $_SESSION['user'] = $customer;
            return $response->withHeader('Location', '/products')->withStatus(302);
        } else {
            return $response->withHeader('Location', '/register?error=registration_failed')->withStatus(302);
        }
    }
}

