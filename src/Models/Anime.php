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

    public $id;
    public $mal_id;
    public $images;
    public $trailer;
    public $approved;
    public $title;
    public $title_english;
    public $title_japanese;
    public $type;
    public $source;
    public $episodes;
    public $status;
    public $airing;
    public $aired;
    public $duration;
    public $rating;
    public $score;
    public $scored_by;
    public $rank;
    public $popularity;
    public $members;
    public $favorites;
    public $synopses;
    public $background;
    public $season;
    public $year;
    public $broadcast;
    public $titles = [];
    public $title_synonyms = [];
    public $producers = [];
    public $licensors = [];
    public $studios = [];
    public $genres = [];
    public $explicit_genres = [];
    public $themes = [];
    public $demographics = [];

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