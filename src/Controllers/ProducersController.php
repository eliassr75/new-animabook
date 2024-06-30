<?php

namespace App\Controllers;

use App\Models\Anime;
use App\Models\Producers;

class ProducersController extends BaseController
{
    public $anime_id;
    public $producers;

    public function checkProducers()
    {
        $functions = new FunctionController();
        $functions->api = true;

        foreach ($this->producers as $producer) {
            if (!empty($producer->name) && !empty($producer->type) && isset($producer->mal_id) && isset($producer->url)) {

                $producer_search = Producers::where('mal_id', $producer->mal_id)->first();
                if (!$producer_search) {

                    $new_producer = new Producers();
                    foreach ($new_producer->allowed_keys as $key) {
                        if (isset($producer->$key)) {
                            $new_producer->$key = $producer->$key;
                        }
                    }
                    if ($new_producer->validate()) {
                        $new_producer->save();

                        $anime = Anime::find($this->anime_id);
                        $anime->producers()->attach($new_producer->id);

                    } else {
                        $functions->sendResponse(['error' => 'Validation failed']);
                        return;
                    }
                } else {
                    $producer_search->update([
                        'name' => $producer->name,
                        'type' => $producer->type,
                        'url' => $producer->url
                    ]);

                    $anime = Anime::find($this->anime_id);
                    if (!$anime->producers->contains($producer_search->id)) {
                        $anime->producers()->attach($producer_search->id);
                    }

                }
            } else {
                $functions->sendResponse(['error' => 'Dados incompletos']);
                return;
            }
        }
        $functions->sendResponse(['success' => 'Producers checked successfully']);
    }

}