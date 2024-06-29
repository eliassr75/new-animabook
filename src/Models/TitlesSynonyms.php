<?php

namespace App\Models;

use App\Validators\Validator;
use Illuminate\Database\Eloquent\Model;

#[\AllowDynamicProperties]
class TitlesSynonyms extends Model
{

    const allowed_keys = [
        'title',
        'anime_id'
    ];

    protected $table = "titles_synonyms";
    protected $fillable = [
        'id',
        'title',
        'anime_id'
    ];

    protected $guarded = ['id'];

    public $timestamps = false;

    public function anime()
    {
        return $this->belongsTo(Anime::class);
    }

    public function validate() {
        Validator::validateTitleSynonyms($this);
    }
}
