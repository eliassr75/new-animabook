<?php

namespace App\Models;

use Exception;
use App\Validators\Validator;
use Ramsey\Uuid\Uuid;

class Anime extends BaseModel
{
    protected $table = "animes";

    protected $columns = [
        'id',
        'mal_id',
        'images',
        'trailer',
        'approved',
        'titles',
        'title',
        'title_english',
        'title_japanese',
        'title_synonyms',
        'type',
        'source',
        'episodes',
        'status',
        'airing',
        'aired',
        'duration',
        'rating',
        'score',
        'scored_by',
        'rank',
        'popularity',
        'members',
        'favorites',
        'synopses',
        'background',
        'season',
        'year',
        'broadcast',
        'producers',
        'licensors',
        'studios',
        'genders',
        'explicit_genres',
        'themes',
        'demographics'
    ];

    public function validate() {

        if(empty($this->token)){
            $this->token = Uuid::uuid4();
        }

        Validator::validateAnime($this);
    }

    public function save() {
        $this->validate();
        parent::save();
    }
}