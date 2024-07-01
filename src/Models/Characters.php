<?php

namespace App\Models;

use App\Validators\Validator;
use Exception;
use Illuminate\Database\Eloquent\Model;

#[\AllowDynamicProperties]
class Characters extends Model
{
    protected $table = "characters";
    protected $fillable = [
        'id',
        'name',
        'images',
        'url',
        'mal_id'
    ];

    protected $guarded = ['id'];

    public $timestamps = false;

    public array $allowed_keys = [
        'name',
        'images',
        'url',
        'mal_id'
    ];

    public function animes()
    {
        return $this->belongsToMany(Anime::class, 'anime_characters_staff', 'character_id', 'anime_id');
    }

    public function staff()
    {
        return $this->belongsToMany(Staff::class, 'anime_characters_staff', 'character_id', 'staff_id')->withPivot('language');
    }

    /**
     * @throws Exception
     */
    public function validate(): bool
    {
        return Validator::validateCharacter($this);
    }
}
