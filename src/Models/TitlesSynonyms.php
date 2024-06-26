<?php

namespace App\Models;

use App\Validators\Validator;
use Exception;
use Illuminate\Database\Eloquent\Model;

#[\AllowDynamicProperties]
class TitlesSynonyms extends Model
{

    public array $allowed_keys = [
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

    /**
     * @throws Exception
     */
    public function validate(): bool
    {
        return Validator::validateTitleSynonyms($this);
    }
}
