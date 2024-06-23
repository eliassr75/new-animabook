<?php

namespace App\Controllers;

use App\Models\MenuItem;

class MenuController extends BaseController
{
    public function getMenuItemsByCategory($category)
    {
        $menuItems = MenuItem::getByCategory($this->pdo, $category);
        return $menuItems;
    }

    public function getAll()
    {
        $menuItems = MenuItem::getAll($this->pdo);
        return $menuItems;
    }

    public function sendResponse($responseData, $statusCode = 200)
    {
        header('Content-Type: application/json', true, $statusCode);
        echo json_encode($responseData, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }

    public function createMenuItem()
    {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!empty($data['title']) && !empty($data['link']) && !empty($data['category'])) {

            if(!isset($data['type'])){
                $data['type'] = "navbar";
            }

            $query = "INSERT INTO menu_items (title, link, category, type) VALUES (:title, :link, :category, :type)";
            $this->pdo->prepare($query)->execute([$data['title'], $data['link'], $data['category'], $data['type']]);


            $this->sendResponse(['message' => 'Categoria criada com sucesso']);
        } else {
            $this->sendResponse(['message' => 'Dados incompletos', 'receive' => json_encode($data, true)], 400);
        }
    }

    public function updateMenuItem()
    {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!empty($data['title']) && !empty($data['link']) && !empty($data['category'] && !empty($category['id']))) {

            $query = "UPDATE menu_items SET title = :title, link = :link, category = :category WHERE id = :id";
            $this->pdo->prepare($query)->execute([$data['title'], $data['link'], $data['category'], $data['id']]);

            $this->sendResponse(['message' => 'Categoria atualizada com sucesso']);
        } else {
            $this->sendResponse(['message' => 'Dados incompletos'], 400);
        }
    }

    public function removeMenuItem()
    {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!empty($category['id'])) {

            $query = "DELETE FROM menu_items WHERE id = :id";
            $this->pdo->prepare($query)->execute([$data['id']]);

            $this->sendResponse(['message' => 'Categoria removida com sucesso']);
        } else {
            $this->sendResponse(['message' => 'Dados incompletos'], 400);
        }
    }

}
