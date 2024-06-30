<?php

namespace App\Controllers;

use App\Models\Anime;
use App\Models\Genres;

class GenresController extends BaseController
{
    public $anime_id;
    public $genres;

    public function checkGenres()
    {
        $functions = new FunctionController();
        $functions->api = true;

        foreach ($this->genres as $genre) {
            if (!empty($genre->name) && !empty($genre->type) && isset($genre->mal_id) && isset($genre->url)) {

                $genre_search = Genres::where('mal_id', $genre->mal_id)->first();
                if (!$genre_search) {

                    $new_genre = new Genres();
                    foreach ($new_genre->allowed_keys as $key) {
                        if (isset($genre->$key)) {
                            $new_genre->$key = $genre->$key;
                        }
                    }
                    if ($new_genre->validate()) {
                        $new_genre->save();

                        $anime = Anime::find($this->anime_id);
                        $anime->genres()->attach($new_genre->id);

                    } else {
                        $functions->sendResponse(['error' => 'Validation failed']);
                        return;
                    }
                } else {
                    $genre_search->update([
                        'name' => $genre->name,
                        'type' => $genre->type,
                        'url' => $genre->url
                    ]);

                    $anime = Anime::find($this->anime_id);
                    if (!$anime->genres->contains($genre_search->id)) {
                        $anime->genres()->attach($genre_search->id);
                    }

                }
            } else {
                $functions->sendResponse(['error' => 'Dados incompletos']);
                return;
            }
        }
        $functions->sendResponse(['success' => 'Genres checked successfully']);
    }

}