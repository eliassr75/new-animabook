<?php

namespace App\Models;

use Exception;
use App\Validators\Validator;

#[\AllowDynamicProperties]
class Titles extends BaseModel
{

    protected $table = "titles";

    protected $columns = [
        'id',
        'title',
        'type',
        'anime_id'
    ];

    public $id;
    public $title;
    public $type;
    public $anime_id;

    public function validate() {
        Validator::validateTitle($this);
    }

    public function save() {
        $this->validate();
        parent::save();
    }
}
