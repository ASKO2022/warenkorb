<?php

session_start();

use Cart\Controllers\RegisterController;
use Cart\Database\Connection;
use Cart\Models\Customer;
use Cart\Models\Product;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;
use DI\Container;


require __DIR__ . '/../vendor/autoload.php';

$container = new Container();
$container->set('view', function () {
    return Twig::create(__DIR__ . '/../templates', ['cache' => false]);
});

$app = AppFactory::createFromContainer($container);
$app->add(TwigMiddleware::createFromContainer($app, 'view'));

$app->get('/', function (Request $request, Response $response, $args) {
    return $this->get('view')->render($response, 'index.html.twig', ['name' => 'Slim Framework']);
});

$app->get('/login', function (Request $request, Response $response, $args) {
    return $this->get('view')->render($response, 'login.html.twig', ['name' => 'Slim Framework']);
});


// Benutzer einloggen
$app->post('/login', function (Request $request, Response $response, $args) {
    $data = $request->getParsedBody();
    $username = $data['username'] ?? '';
    $password = $data['password'] ?? '';

    $customer = new Customer();
    $loginResult = $customer->login($username, $password);

    if (is_array($loginResult)) {
        $_SESSION['user'] = $loginResult;
        return $response->withHeader('Location', '/products')->withStatus(302);
    } else {
        return $this->get('view')->render($response, 'login.html.twig', [
            'errorMessage' => $loginResult
        ]);
    }
});


$app->get('/products', function (Request $request, Response $response, $args) {
    $product = new Product();
    $products = $product->getAllProducts();

    return $this->get('view')->render($response, 'products.html.twig', [
        'products' => $products,
        'user' => $_SESSION['user'] ?? null
    ]);
});
;

$app->post('/products', function (Request $request, Response $response, $args) {
    if (!isset($_SESSION['user'])) {
        // Wenn der Benutzer nicht eingeloggt ist, leite ihn zur Login-Seite weiter
        return $response->withHeader('Location', '/login')->withStatus(302);
    }

    $data = $request->getParsedBody();
    $name = $data['name'] ?? '';
    $description = $data['description'] ?? '';
    $price = $data['price'] ?? '';

    if (empty($name) || empty($description) || empty($price)) {
        $response->getBody()->write('Alle Felder sind erforderlich.');
        return $response->withStatus(400);
    }

    $product = new Product();
    $result = $product->addProduct($name, $description, $price);

    if ($result === true) {
        return $response->withHeader('Location', '/products')->withStatus(302);
    } else {
        $response->getBody()->write('Fehler beim Hinzufügen des Produkts: ' . $result);
        return $response->withStatus(500);
    }
});


//Benutzer in Datenbank hinlegen
$app->get('/register', function (Request $request, Response $response, $args) {
    return $this->get('view')->render($response, 'register.html.twig');
});

//Registrieren
$app->post('/register', RegisterController::class . ':register');


// TODO: Wenn Register erfolgreis registriert dann Login Formular ausbleden und Logout einblenden
// Session deaktivieren
$app->get('/logout', function (Request $request, Response $response, $args) {
    session_unset();
    session_destroy();

    return $response->withHeader('Location', '/login')->withStatus(302);
});


$app->run();
