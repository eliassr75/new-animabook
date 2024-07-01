<?php

namespace App\Models;

use App\Validators\Validator;
use Exception;
use Illuminate\Database\Eloquent\Model;

#[\AllowDynamicProperties]
class Positions extends Model
{
    protected $table = "characters";
    protected $fillable = [
        'id',
        'name',
    ];

    protected $guarded = ['id'];

    public $timestamps = false;

    public array $allowed_keys = [
        'name',
    ];

    public function staff()
    {
        return $this->belongsToMany(Staff::class, 'staff_positions', 'positions_id', 'staff_id')->withPivot('anime_id');
    }

    /**
     * @throws Exception
     */
    public function validate(): bool
    {
        return Validator::validatePositions($this);
    }
}
