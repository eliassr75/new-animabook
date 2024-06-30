<?php

namespace App\Controllers;

use App\Models\Anime;
use App\Models\Themes;

class ThemesController extends BaseController
{
    public $anime_id;
    public $themes;

    public function checkThemes()
    {
        $functions = new FunctionController();
        $functions->api = true;

        foreach ($this->themes as $theme) {
            if (!empty($theme->name) && !empty($theme->type) && isset($theme->mal_id) && isset($theme->url)) {

                $theme_search = Themes::where('mal_id', $theme->mal_id)->first();
                if (!$theme_search) {

                    $new_theme = new Themes();
                    foreach ($new_theme->allowed_keys as $key) {
                        if (isset($theme->$key)) {
                            $new_theme->$key = $theme->$key;
                        }
                    }
                    if ($new_theme->validate()) {
                        $new_theme->save();

                        $anime = Anime::find($this->anime_id);
                        $anime->themes()->attach($new_theme->id);

                    } else {
                        $functions->sendResponse(['error' => 'Validation failed']);
                        return;
                    }
                } else {
                    $theme_search->update([
                        'name' => $theme->name,
                        'type' => $theme->type,
                        'url' => $theme->url
                    ]);

                    $anime = Anime::find($this->anime_id);
                    if (!$anime->themes->contains($theme_search->id)) {
                        $anime->themes()->attach($theme_search->id);
                    }

                }
            } else {
                $functions->sendResponse(['error' => 'Dados incompletos']);
                return;
            }
        }
        $functions->sendResponse(['success' => 'Themes checked successfully']);
    }

}