<?php

namespace App\Controllers;

use App\Models\Titles;

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

                    $titlesModel = new Titles();
                    foreach ($titlesModel->allowed_keys as $key) {
                        if ($key == "anime_id") {
                            $titlesModel->anime_id = $this->anime_id;
                        }else{
                            $titlesModel->$key = $title->$key;
                        }
                    }
                    if($titlesModel->validate()){
                        $titlesModel->save();
                    }
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