<?php

namespace App\Models;

use Exception;
use App\Validators\Validator;
use Ramsey\Uuid\Uuid;

class User extends BaseModel {
    protected $table = 'users';
    protected $columns = [
        'id',
        'name',
        'email',
        'username',
        'password',
        'gender',
        'token',
        'created',
        'updated'
    ];

    public $id;
    public $name;
    public $email;
    public $username;
    public $password;
    public $gender;
    public $token;
    public $created;
    public $updated;

    public function validate() {

        if(empty($this->token)){
            $this->token = Uuid::uuid4();
        }

        Validator::validateUser($this);
    }

    public function save() {
        $this->validate();
        parent::save();
    }
}
?>
