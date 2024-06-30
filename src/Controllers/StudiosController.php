<?php

namespace App\Controllers;

use App\Models\Anime;
use App\Models\Studios;

class StudiosController extends BaseController
{
    public $anime_id;
    public $studios;

    public function checkStudios()
    {
        $functions = new FunctionController();
        $functions->api = true;

        foreach ($this->studios as $studio) {
            if (!empty($studio->name) && !empty($studio->type) && isset($studio->mal_id) && isset($studio->url)) {

                $studio_search = Studios::where('mal_id', $studio->mal_id)->first();
                if (!$studio_search) {

                    $new_studio = new Studios();
                    foreach ($new_studio->allowed_keys as $key) {
                        if (isset($studio->$key)) {
                            $new_studio->$key = $studio->$key;
                        }
                    }
                    if ($new_studio->validate()) {
                        $new_studio->save();

                        $anime = Anime::find($this->anime_id);
                        $anime->studios()->attach($new_studio->id);

                    } else {
                        $functions->sendResponse(['error' => 'Validation failed']);
                        return;
                    }
                } else {
                    $studio_search->update([
                        'name' => $studio->name,
                        'type' => $studio->type,
                        'url' => $studio->url
                    ]);

                    $anime = Anime::find($this->anime_id);
                    if (!$anime->studios->contains($studio_search->id)) {
                        $anime->studios()->attach($studio_search->id);
                    }

                }
            } else {
                $functions->sendResponse(['error' => 'Dados incompletos']);
                return;
            }
        }
        $functions->sendResponse(['success' => 'Studios checked successfully']);
    }

}