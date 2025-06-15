<?php
namespace User\Scandiweb;

use PDO;

class ProductRepository {
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function getAllProducts(): array {
        $stmt = $this->db->query("SELECT * FROM products");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addProduct(Product $product): bool {
        $stmt = $this->db->prepare("
            INSERT INTO products (sku, name, price, type, attribute) 
            VALUES (:sku, :name, :price, :type, :attribute)
        ");

        return $stmt->execute([
            ':sku' => $product->getSku(),
            ':name' => $product->getName(),
            ':price' => $product->getPrice(),
            ':type' => $product->getType(),
            ':attribute' => $product->getAttribute()
        ]);
    }

    public function deleteBySkus(array $skus): bool {
        if (empty($skus)) {
            return false;
        }

        $placeholders = rtrim(str_repeat('?,', count($skus)), ',');
        $stmt = $this->db->prepare("DELETE FROM products WHERE sku IN ($placeholders)");
        return $stmt->execute($skus);
    }
}
