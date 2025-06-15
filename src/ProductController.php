<?php
namespace User\Scandiweb;

class ProductController {
    private ProductRepository $repo;

    public function __construct(ProductRepository $repo) {
        $this->repo = $repo;
    }

    public function listProducts(): array {
        return $this->repo->getAllProducts();
    }

    public function createProduct(array $data): bool {
        $product = new Product(
            0,
            $data['sku'],
            $data['name'],
            (float)$data['price'],
            $data['type'],
            $data['attribute']
        );

        return $this->repo->addProduct($product);
    }
}
