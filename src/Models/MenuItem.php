<?php

namespace App\Models;
use PDO;

class MenuItem
{
    public $id;
    public $title;
    public $link;
    public $category;
    public $isVisible;

    public static function getByCategory($pdo, $category)
    {
        $stmt = $pdo->prepare("SELECT id, title, link, is_visible FROM menu_items WHERE is_visible = 'true' AND category = :category order by title");
        $stmt->execute(['category' => $category]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getAll($pdo)
    {
        $stmt = $pdo->prepare("SELECT * FROM menu_items order by title");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
