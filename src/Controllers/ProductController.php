<?php

namespace Cart\Controllers;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Cart\Services\ProductService;

class ProductController
{
    protected $container;
    protected $productService;

    public function __construct(ContainerInterface $container, ProductService $productService)
    {
        $this->container = $container;
        $this->productService = $productService;
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getAllProducts(Request $request, Response $response, $args): Response
    {
        $products = $this->productService->getAllProducts();
        $view = $this->container->get('view');
        return $view->render($response, 'products.html.twig', [
            'products' => $products,
            'user' => $_SESSION['user'] ?? null,
        ]);
    }

    public function addProduct(Request $request, Response $response, $args): Response
    {
        $data = $request->getParsedBody();
        $name = $data['name'] ?? '';
        $description = $data['description'] ?? '';
        $price = $data['price'] ?? '';

        if (empty($name) || empty($description) || empty($price)) {
            return $response->withStatus(400, 'Fehler beim Hinzufügen des Produkts');
        }

        $result = $this->productService->addProduct($name, $description, $price);
        return $response->withHeader('Location', '/products')->withStatus(302);
    }


    public function updateProduct(Request $request, Response $response, $args): Response
    {
        $data = $request->getParsedBody();
        $id = $data['id'];
        $name = $data['name'];
        $description = $data['description'];
        $price = $data['price'];

        $result = $this->productService->updateProduct($id, $name, $description, $price);
        if ($result === false) {
            return $response->withStatus(500, 'Fehler beim Aktualisieren des Produkts');
        }
        return $response->withHeader('Location','/products')->withStatus(302);
    }


    public function deleteProduct(Request $request, Response $response, $args): Response
    {
        $id = $args['id'] ?? null;

        if (!$id || !is_numeric($id)) {
            return $response->withStatus(400, 'Ungültige ID');
        }

        $success = $this->productService->deleteProduct($id);

        if (!$success) {
            return $response->withStatus(500, 'Fehler beim Löschen oder Produkt nicht gefunden');
        }

        return $response->withHeader('Location', '/products')->withStatus(302);
    }

}
