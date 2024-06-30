<?php

namespace App\Controllers;

use App\Models\Anime;
use App\Models\ExplicitGenres;

class ExplicitGenresController extends BaseController
{
    public $anime_id;
    public $explicit_genres;

    public function checkExplicitGenres()
    {
        $functions = new FunctionController();
        $functions->api = true;

        foreach ($this->explicit_genres as $explicit_genre) {
            if (!empty($explicit_genre->name) && !empty($explicit_genre->type) && isset($explicit_genre->mal_id) && isset($explicit_genre->url)) {

                $explicit_genre_search = ExplicitGenres::where('mal_id', $explicit_genre->mal_id)->first();
                if (!$explicit_genre_search) {

                    $new_explicit_genre = new ExplicitGenres();
                    foreach ($new_explicit_genre->allowed_keys as $key) {
                        if (isset($explicit_genre->$key)) {
                            $new_explicit_genre->$key = $explicit_genre->$key;
                        }
                    }
                    if ($new_explicit_genre->validate()) {
                        $new_explicit_genre->save();

                        $anime = Anime::find($this->anime_id);
                        $anime->explicit_genres()->attach($new_explicit_genre->id);

                    } else {
                        $functions->sendResponse(['error' => 'Validation failed']);
                        return;
                    }
                } else {
                    $explicit_genre_search->update([
                        'name' => $explicit_genre->name,
                        'type' => $explicit_genre->type,
                        'url' => $explicit_genre->url
                    ]);

                    $anime = Anime::find($this->anime_id);
                    if (!$anime->explicit_genres->contains($explicit_genre_search->id)) {
                        $anime->explicit_genres()->attach($explicit_genre_search->id);
                    }

                }
            } else {
                $functions->sendResponse(['error' => 'Dados incompletos']);
                return;
            }
        }
        $functions->sendResponse(['success' => 'ExplicitGenres checked successfully']);
    }

}