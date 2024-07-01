<?php

namespace App\Models;

use App\Validators\Validator;
use Exception;
use Illuminate\Database\Eloquent\Model;

#[\AllowDynamicProperties]
class Staff extends Model
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
        return $this->belongsToMany(Anime::class, 'anime_characters_staff', 'staff_id', 'anime_id');
    }

    public function characters()
    {
        return $this->belongsToMany(Characters::class, 'anime_characters_staff', 'staff_id', 'character_id')->withPivot('language');
    }

    public function positions()
    {
        return $this->belongsToMany(Positions::class, 'staff_positions', 'staff_id', 'position_id')->withPivot('anime_id');
    }

    /**
     * @throws Exception
     */
    public function validate(): bool
    {
        return Validator::validateStaff($this);
    }
}
