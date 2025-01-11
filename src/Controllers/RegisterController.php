<?php

namespace Cart\Controllers;

use Cart\Models\Customer;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class RegisterController
{
    // Die Methode für die Registrierung des Nutzers
    public function register(Request $request, Response $response, $args)
    {
        $data = $request->getParsedBody();
        $username = $data['username'];
        $email = $data['email'];
        $password = $data['password'];
        $confirmPassword = $data['confirm_password'];

        $customer = new Customer();
        $result = $customer->register($username, $email, $password);

        if ($result === "Registrierung erfolgreich!") {
            return $response->withHeader('Location', '/login')->withStatus(302);
        }
    }

}
