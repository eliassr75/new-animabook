<?php

require_once '../vendor/autoload.php';
require_once '../config/database.php';

use App\Controllers\UserController;

$config = require '../config/database.php';

try {

    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

    if ($uri === '/home/users' && $_SERVER['REQUEST_METHOD'] === 'GET') {
        $controller = new UserController();
        $controller->getAllUsers();
    } elseif ($uri === '/home/users' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $controller = new UserController();
        $controller->createUser();
    } else {
        header("HTTP/1.1 404 Not Found");
        echo json_encode(['message' => 'Endpoint não encontrado']);
    }

} catch (Exception $e) {
    echo "Erro: " . $e->getMessage();
}
?>
