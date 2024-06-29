<?php

namespace App\Controllers;

use App\Models\Anime;
use App\Models\TitlesSynonyms;
use GuzzleHttp\Client;

class TitlesSynonymsController extends BaseController
{

    public $anime_id;
    public $titles;

    public function get_all()
    {
        $titlesModel = new TitlesSynonyms();
        return $titlesModel->where('anime_id', $this->anime_id)->all();
    }

    public function checkTitlesSynonyms()
    {
        $functions = new FunctionController();
        $functions->api = true;

        foreach ($this->titles as $title) {
            if (!empty($title) && isset($this->anime_id)) {

                $title_search = TitlesSynonyms::where('title', $title)
                    ->where('anime_id', $this->anime_id)
                    ->first();

                if (!$title_search) {
                    $stmt = [];
                    foreach (TitlesSynonyms::allowed_keys as $key) {
                        if ($key == "anime_id") {
                            $stmt['anime_id'] = $this->anime_id;
                        }else{
                            $stmt[$key] = $title;
                        }
                    }
                    TitlesSynonyms::create($stmt);
                } else {
                    $title_search->update([
                        'title' => $title,
                        'anime_id' => $this->anime_id
                    ]);
                }
            } else {
                $functions->sendResponse(['error' => 'Dados incompletos']);
                return;
            }
        }
        $functions->sendResponse(['success' => 'Titles Synonyms checked successfully']);
    }
}