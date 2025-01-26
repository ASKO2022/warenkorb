<?php

session_start();

use Cart\Controllers\LoginController;
use Cart\Controllers\RegisterController;
use Cart\Controllers\ProductController;
use Cart\Models\Product;
use Psr\Container\ContainerInterface;
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
    return $this->get('view')->render($response, 'index.html.twig');
});

$app->get('/login', LoginController::class . ':showLoginForm');
$app->post('/login', LoginController::class . ':login');


$app->get('/products', ProductController::class . ':getAllProducts');
$app->post('/products', ProductController::class . ':addProduct');


$app->get('/register', RegisterController::class . ':showRegisterForm');
$app->post('/register', RegisterController::class . ':register');


$app->get('/logout', LoginController::class . ':logout');


$app->run();
