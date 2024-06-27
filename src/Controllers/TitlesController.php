<?php

namespace App\Controllers;

use App\Models\Anime;
use App\Models\Titles;
use GuzzleHttp\Client;

class TitlesController extends BaseController
{

    public int $anime_id = 0;
    public function createTitle(): void
    {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!empty($data['title']) && !empty($data['type']) && !empty($data['anime_id'])) {
            $user = new Titles($this->pdo);
            $user->title = $data['title'];
            $user->type = $data['type'];
            $user->anime_id = $data['anime_id'];
            $user->save();

            header('Content-Type: application/json');
            echo json_encode(['message' => 'Title criado com sucesso']);
        } else {
            header('Content-Type: application/json', true, 400);
            echo json_encode(['message' => 'Dados incompletos']);
        }
    }

    public function createTitleByApi($titles): void
    {

        $functions = new FunctionController();
        $functions->api = true;

        $inserteds = [];
        foreach ($titles as $title) {
            if (!empty($title->title) && !empty($title->type) && !$this->anime_id) {

                $titles = new Titles($this->pdo);
                $titles->title = $title->title;
                $titles->type = $title->type;
                $titles->anime_id = $this->anime_id;
                $titles->save();

                $inserteds[] = $titles->title;
            } else {
                $functions->sendResponse(['message' => 'Dados incompletos'], 400);
            }
        }
        $functions->sendResponse(['message' => 'Títulos ' . implode(', ', $inserteds) .  ' criados com sucesso'], 400);

    }

}