<?php

namespace App\Controllers;

use App\Models\Anime;
use App\Models\Licensors;

class LicensorsController extends BaseController
{
    public $anime_id;
    public $licensors;

    public function checkLicensors()
    {
        $functions = new FunctionController();
        $functions->api = true;

        foreach ($this->licensors as $licensor) {
            if (!empty($licensor->name) && !empty($licensor->type) && isset($licensor->mal_id) && isset($licensor->url)) {

                $licensor_search = Licensors::where('mal_id', $licensor->mal_id)->first();
                if (!$licensor_search) {

                    $new_licensor = new Licensors();
                    foreach ($new_licensor->allowed_keys as $key) {
                        if (isset($licensor->$key)) {
                            $new_licensor->$key = $licensor->$key;
                        }
                    }
                    if ($new_licensor->validate()) {
                        $new_licensor->save();

                        $anime = Anime::find($this->anime_id);
                        $anime->licensors()->attach($new_licensor->id);

                    } else {
                        $functions->sendResponse(['error' => 'Validation failed']);
                        return;
                    }
                } else {
                    $licensor_search->update([
                        'name' => $licensor->name,
                        'type' => $licensor->type,
                        'url' => $licensor->url
                    ]);

                    $anime = Anime::find($this->anime_id);
                    if (!$anime->licensors->contains($licensor_search->id)) {
                        $anime->licensors()->attach($licensor_search->id);
                    }

                }
            } else {
                $functions->sendResponse(['error' => 'Dados incompletos']);
                return;
            }
        }
        $functions->sendResponse(['success' => 'Licensors checked successfully']);
    }

}