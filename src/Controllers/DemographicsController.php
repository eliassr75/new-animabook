<?php

namespace App\Controllers;

use App\Models\Anime;
use App\Models\Demographics;

class DemographicsController extends BaseController
{
    public $anime_id;
    public $demographics;

    public function checkDemographics()
    {
        $functions = new FunctionController();
        $functions->api = true;

        foreach ($this->demographics as $demographic) {
            if (!empty($demographic->name) && !empty($demographic->type) && isset($demographic->mal_id) && isset($demographic->url)) {

                $demographic_search = Demographics::where('mal_id', $demographic->mal_id)->first();
                if (!$demographic_search) {

                    $new_demographic = new Demographics();
                    foreach ($new_demographic->allowed_keys as $key) {
                        if (isset($demographic->$key)) {
                            $new_demographic->$key = $demographic->$key;
                        }
                    }
                    if ($new_demographic->validate()) {
                        $new_demographic->save();

                        $anime = Anime::find($this->anime_id);
                        $anime->demographics()->attach($new_demographic->id);

                    } else {
                        $functions->sendResponse(['error' => 'Validation failed']);
                        return;
                    }
                } else {
                    $demographic_search->update([
                        'name' => $demographic->name,
                        'type' => $demographic->type,
                        'url' => $demographic->url
                    ]);

                    $anime = Anime::find($this->anime_id);
                    if (!$anime->demographics->contains($demographic_search->id)) {
                        $anime->demographics()->attach($demographic_search->id);
                    }

                }
            } else {
                $functions->sendResponse(['error' => 'Dados incompletos']);
                return;
            }
        }
        $functions->sendResponse(['success' => 'Demographics checked successfully']);
    }

}