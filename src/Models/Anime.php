<?php

namespace App\Models;

use App\Validators\Validator;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

#[\AllowDynamicProperties]
class Anime extends Model
{
    protected $table = "anime";
    protected $fillable = [
        'id',
        'mal_id',
        'url',
        'images',
        'trailer',
        'approved',
        'title',
        'title_english',
        'title_japanese',
        'type',
        'source',
        'episodes_counter',
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
        'synopsis',
        'background',
        'season',
        'year',
        'broadcast',
        'theme',
        'updated_at',
        'created_at',
        'active'
    ];

    protected $guarded = ['id'];
    public $allowed_keys = [
        'mal_id',
        'url',
        'images',
        'trailer',
        'approved',
        'title',
        'title_english',
        'title_japanese',
        'type',
        'source',
        'episodes_counter',
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
        'synopsis',
        'background',
        'season',
        'year',
        'broadcast',
        'theme',
    ];

    public function titles()
    {
        return $this->hasMany(Titles::class);
    }

    public function titles_synonyms()
    {
        return $this->hasMany(TitlesSynonyms::class);
    }

    public function validate()
    {
        if (empty($this->token)) {
            $this->token = Uuid::uuid4();
        }

        Validator::validateAnime($this);
    }

}
