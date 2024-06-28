<?php

namespace App\Controllers;

use App\Models\Anime;
use App\Models\Titles;
use GuzzleHttp\Client;

class TitlesController extends BaseController
{

    public $anime_id;
    public $titles;

    public function get_all()
    {
        $titlesModel = new Titles($this->pdo);
        return $titlesModel->where('anime_id', '=', $this->anime_id)->get_all();
    }

    public function checkTitles(): void
    {
        $functions = new FunctionController();

        foreach ($this->titles as $title) {
            if (!empty($title->title) && !empty($title->type) && $this->anime_id) {

                $titleModel = new Titles($this->pdo);
                $title_search = $titleModel
                    ->where('type', '=', $title->type)
                    ->where('anime_id', '=', $this->anime_id)
                    ->get();

                if (!$title_search) {
                    $titleModel->title = $title->title;
                    $titleModel->type = $title->type;
                    $titleModel->anime_id = $this->anime_id;
                    $titleModel->save();
                } else {
                    $title_search = $titleModel->find($this->pdo, $title_search->id);
                    $title_search->title = $title->title;
                    $title_search->type = $title->type;
                    $title_search->anime_id = $this->anime_id;
                    $title_search->save();
                }
            } else {
                $functions->sendResponse(['error' => 'Dados incompletos']);
            }
        }
        $functions->sendResponse(['success' => 'Titles checked successfully']);
    }
}