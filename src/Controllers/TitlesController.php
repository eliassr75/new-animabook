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
        $titlesModel = new Titles();
        return $titlesModel->where('anime_id', $this->anime_id)->all();
    }

    public function checkTitles()
    {
        $functions = new FunctionController();
        $functions->api = true;

        foreach ($this->titles as $title) {
            if (!empty($title->title) && !empty($title->type) && isset($this->anime_id)) {

                $title_search = Titles::where('type', $title->type)
                    ->where('title', $title->title)
                    ->where('anime_id', $this->anime_id)
                    ->first();

                if (!$title_search) {
                    $stmt = [];
                    foreach (Titles::allowed_keys as $key) {
                        if ($key == "anime_id") {
                            $stmt['anime_id'] = $this->anime_id;
                        }else{
                            $stmt[$key] = $title->$key;
                        }
                    }
                    Titles::create($stmt);
                } else {
                    $title_search->update([
                        'title' => $title->title,
                        'type' => $title->type,
                        'anime_id' => $this->anime_id
                    ]);
                }
            } else {
                $functions->sendResponse(['error' => 'Dados incompletos']);
                return;
            }
        }
        $functions->sendResponse(['success' => 'Titles checked successfully']);
    }
}