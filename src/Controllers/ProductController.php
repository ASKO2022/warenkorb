<?php

namespace Cart\Controllers;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Cart\Models\Product;

class ProductController
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getAllProducts(Request $request, Response $response, $args)
    {
        $product = new Product();
        $products = $product->getAllProducts();

        $view = $this->container->get('view');

        return $view->render($response, 'products.html.twig', [
            'products' => $products,
            'user' => $_SESSION['user'] ?? null,
        ]);
    }

    public function addProduct(Request $request, Response $response, $args)
    {
        if (!isset($_SESSION['user'])) {
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
    }
}
