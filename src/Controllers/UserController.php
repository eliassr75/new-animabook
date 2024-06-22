<?php

namespace App\Controllers;

use App\Models\User;

class UserController extends BaseController
{
    public function getAllUsers()
    {
        $users = User::all($this->pdo);
        header('Content-Type: application/json');
        echo json_encode($users);
    }

    public function createUser()
    {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!empty($data['name']) && !empty($data['email']) && !empty($data['password'])) {
            $user = new User($this->pdo);
            $user->name = $data['name'];
            $user->email = $data['email'];
            $user->password = password_hash($data['password'], PASSWORD_DEFAULT);
            $user->save();

            header('Content-Type: application/json');
            echo json_encode(['message' => 'Usuário criado com sucesso']);
        } else {
            header('Content-Type: application/json', true, 400);
            echo json_encode(['message' => 'Dados incompletos']);
        }
    }
}
