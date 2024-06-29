<?php

namespace App\Models;

use Exception;
use App\Validators\Validator;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class User extends Model {
    protected $table = 'users';
    protected $columns = [
        'id',
        'name',
        'email',
        'username',
        'password',
        'gender',
        'token',
        'avatar',
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
    public $avatar;
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
