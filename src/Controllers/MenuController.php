<?php

namespace App\Controllers;

use App\Models\MenuItem;

class MenuController extends BaseController
{
    public function getMenuItemsByCategory($category)
    {
        $menuItems = MenuItem::getByCategory($category);
        return $menuItems;
    }

    public function getAll()
    {
        $menuItems = MenuItem::getAll($this->pdo);
        return $menuItems;
    }

    public function createMenuItem()
    {
        $functions = new FunctionController();
        $menuItemModel = new MenuItem();

        $data = json_decode(file_get_contents('php://input'), true);

        if (!empty($data['title']) && !empty($data['link']) && !empty($data['category'])) {

            if(!isset($data['type'])){
                $data['type'] = "navbar";
            }

            $stmt = [
                "title" => $data['title'],
                "link" => $data['link'],
                "category" => $data['category'],
                "type" => $data['type']
            ];

            $menuItemModel->create($stmt);

            $functions->sendResponse(['message' => 'Categoria criada com sucesso']);
        } else {
            $functions->sendResponse(['message' => 'Dados incompletos', 'receive' => json_encode($data, true)], 400);
        }
    }

    public function updateMenuItem()
    {

        $functions = new FunctionController();
        $menuItemModel = new MenuItem();

        $data = json_decode(file_get_contents('php://input'), true);

        if (!empty($data['title']) && !empty($data['link']) && !empty($data['category'] && !empty($data['id']))) {

            $menuItem = $menuItemModel->find($data['id']);

            $stmt = [
                "title" => $data['title'],
                "link" => $data['link'],
                "category" => $data['category'],
                "type" => $data['type']
            ];

            $menuItem->update($stmt);
            $functions->sendResponse(['message' => 'Categoria atualizada com sucesso']);
        } else {
            $functions->sendResponse(['message' => 'Dados incompletos'], 400);
        }
    }

    public function removeMenuItem()
    {

        $functions = new FunctionController();
        $menuItemModel = new MenuItem();

        $data = json_decode(file_get_contents('php://input'), true);

        if (!empty($data['id'])) {
            $menuItemModel->where('id', $data['id'])->delete();
            $functions->sendResponse(['message' => 'Categoria removida com sucesso']);
        } else {
            $functions->sendResponse(['message' => 'Dados incompletos'], 400);
        }
    }

}
