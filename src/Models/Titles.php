<?php

namespace App\Models;

use App\Validators\Validator;
use Exception;
use Illuminate\Database\Eloquent\Model;

#[\AllowDynamicProperties]
class Titles extends Model
{

    public array $allowed_keys = [
        'title',
        'type',
        'anime_id'
    ];

    protected $table = "titles";
    protected $fillable = [
        'id',
        'title',
        'type',
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
        return Validator::validateTitle($this);
    }
}
