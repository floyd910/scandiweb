<?php
require_once __DIR__ . '/../vendor/autoload.php';

use User\SvandiwebTest\ProductRepository;
use User\SvandiwebTest\Database;

header("Content-Type: application/json");

$db = (new Database())->getConnection();
$repository = new ProductRepository($db);

// Get the raw POST body
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['skus']) || !is_array($data['skus'])) {
    http_response_code(400);
    echo json_encode(["error" => "Invalid request body"]);
    exit;
}

$success = $repository->deleteBySkus($data['skus']);

if ($success) {
    echo json_encode(["success" => true]);
} else {
    http_response_code(500);
    echo json_encode(["error" => "Failed to delete products"]);
}
