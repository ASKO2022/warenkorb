<?php

namespace Cart\Controllers;

use Cart\Models\Customer;
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
        $view = $this->container->get('view');

        return $view->render($response, 'login.html.twig');
    }
    public function login(Request $request, Response $response, $args)
    {
        $data = $request->getParsedBody();
        $username = $data['username'] ?? '';
        $password = $data['password'] ?? '';

        $customer = new Customer();
        $loginResult = $customer->login($username, $password);

        if (is_array($loginResult)) {
            $_SESSION['user'] = $loginResult;
            return $response->withHeader('Location', '/products')->withStatus(302);
        } else {
            return $response->getBody()->write($this->get('view')->render($response, 'login.html.twig', [
                'errorMessage' => $loginResult
            ]));
        }
    }

    public function logout(Request $request, Response $response, $args)
    {
        session_unset();
        session_destroy();

        return $response->withHeader('Location', '/login')->withStatus(302);
    }

}
