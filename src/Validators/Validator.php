<?php

namespace App\Validators;

use App\Models\User;
use App\Models\Anime;
use Exception;

class Validator {
    public static function validateUser(User $user) {
        if (empty($user->name)) {
            throw new Exception("O nome não pode ser nulo.");
        }
        if (empty($user->email)) {
            throw new Exception("O email não pode ser nulo.");
        }
        if (!filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Formato de email inválido.");
        }
        if (empty($user->password)) {
            throw new Exception("A senha não pode ser nula.");
        }
        if (empty($user->username)) {
            throw new Exception("O username é obrigatório.");
        }
    }

    public static function validateAnime(Anime $anime) {
        if (empty($anime->name_default)) {
            throw new Exception("O nome padrão não pode ser nulo.");
        }
    }
}
?>
