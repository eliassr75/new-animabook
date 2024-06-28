<?php

namespace App\Controllers;

use App\Models\Anime;
use App\Models\Titles;
use GuzzleHttp\Client;

class TitlesController extends BaseController
{

    public $anime_id;

    public function get_all()
    {
        $titlesModel = new Titles($this->pdo);
        return $titlesModel->where('anime_id', '=', $this->anime_id)->get_all();
    }

    public function checkTitles($titles)
    {
        $functions = new FunctionController();

        foreach ($titles as $title) {
            if (!empty($title->title) && !empty($title->type) && !$this->anime_id) {

                $titleModel = new Titles($this->pdo);
                $title_search = $titleModel->
                where('title', 'like', "%{$title->title}%")->
                where('type', 'like', "%{$title->type}%")->
                where('anime_id', '=', $this->anime_id)->
                get();

                if(!$title_search) {

                    $titleModel->title = $title->title;
                    $titleModel->type = $title->type;
                    $titleModel->anime_id = $this->anime_id;
                    $titleModel->save();

                }else{
                    $stmt = [
                        'title' => $title->title,
                        'type' => $title->type
                    ];
                    $titleModel->where('id', '=', $title->id)->update($stmt);
                }

            } else {
                $functions->sendResponse(['error' => 'Dados incompletos']);
            }
        }
        $functions->sendResponse(['success' => 'Titles checked successfully']);
    }
}