<?php

namespace App\Controllers;

use PDO;

class BaseController
{
    protected $pdo;

    public function __construct()
    {
        $config = require '../config/database.php';
        $this->pdo = new PDO($config['dsn'], $config['username'], $config['password']);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    protected function render($view, $data = [])
    {
        extract($data);
        require "../src/Views/{$view}/index.php";
    }
}
