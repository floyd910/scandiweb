<?php
require_once __DIR__ . '/../vendor/autoload.php';

use User\Scandiweb\ProductRepository;
use User\Scandiweb\ProductController;

// === CORS HEADERS ===
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// === MYSQL CONNECTION (RAILWAY) ===
$host = 'mysql.railway.internal';
$port = 3306;
$db   = 'railway'; // Your database name from Railway credentials
$user = 'root';
$pass = 'dMpTthwQWGaAOxuVDllLAVLlYqgCRuKL';

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // For debugging only, remove on production:
    // echo "✅ Connected successfully<br>";
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed', 'details' => $e->getMessage()]);
    exit();
}

// === INIT CONTROLLER ===
$productRepo = new ProductRepository($pdo);
$productController = new ProductController($productRepo);

// === ROUTING ===
$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$scriptName = dirname($_SERVER['SCRIPT_NAME']);

$scriptPath = '/scandiweb/src/index.php'; // adjust if your index.php path is different

if (strpos($path, $scriptPath) === 0) {
    $path = substr($path, strlen($scriptPath));
}
$path = rtrim($path, '/');

if (strpos($path, $scriptName) === 0) {
    $path = substr($path, strlen($scriptName));
}
$path = rtrim($path, '/');

header('Content-Type: application/json');

// === GET /products ===
if ($method === 'GET' && $path === '/products') {
    $products = $productController->listProducts();
    echo json_encode($products);
    exit;
}

// === POST /products ===
if ($method === 'POST' && $path === '/products') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!$data || !isset($data['sku'], $data['name'], $data['price'], $data['type'], $data['attribute'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid input']);
        exit;
    }

    $success = $productController->createProduct($data);
    if ($success) {
        echo json_encode(['message' => 'Product created']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to create product']);
    }
    exit;
}

// === POST /products/delete ===
if ($method === 'POST' && $path === '/products/delete') {
    $raw = file_get_contents('php://input');
    $data = json_decode($raw, true);

    if (!$data || !isset($data['skus']) || !is_array($data['skus'])) {
        http_response_code(400);
        echo json_encode([
            'error' => 'Invalid input',
            'raw_input' => $raw,
            'decoded_input' => $data
        ]);
        exit;
    }

    $success = $productRepo->deleteBySkus($data['skus']);
    if ($success) {
        echo json_encode(['message' => 'Products deleted']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to delete products']);
    }
    exit;
}

// === 404 fallback ===
http_response_code(404);
echo json_encode(['error' => 'Endpoint not found']);
