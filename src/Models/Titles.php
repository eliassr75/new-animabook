<?php

namespace App\Models;

use App\Validators\Validator;
use Illuminate\Database\Eloquent\Model;

#[\AllowDynamicProperties]
class Titles extends Model
{

    const allowed_keys = [
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

    public function validate() {
        Validator::validateTitle($this);
    }
}
