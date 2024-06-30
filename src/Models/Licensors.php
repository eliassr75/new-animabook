<?php

namespace App\Models;

use App\Validators\Validator;
use Exception;
use Illuminate\Database\Eloquent\Model;

#[\AllowDynamicProperties]
class Licensors extends Model
{
    protected $table = "licensors";
    protected $fillable = [
        'id',
        'name',
        'type',
        'url',
        'mal_id'
    ];

    protected $guarded = ['id'];

    public $timestamps = false;

    public array $allowed_keys = [
        'name',
        'type',
        'url',
        'mal_id'
    ];

    public function animes()
    {
        return $this->belongsToMany(Anime::class, 'relation_licensor_anime', 'licensor_id', 'anime_id');
    }

    /**
     * @throws Exception
     */
    public function validate(): bool
    {
        return Validator::validateLicensors($this);
    }
}
