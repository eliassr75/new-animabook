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
        if(!is_array($anime->titles)){
            throw new Exception("Os títulos devem ser um array.");
        }
        if(!is_array($anime->title_synonyms)){
            throw new Exception("Os sinônimos de titulos devem ser um array.");
        }
        if(!is_array($anime->producers)){
            throw new Exception("Os produtores devem ser um array.");
        }
        if(!is_array($anime->licensors)){
            throw new Exception("Os licenciadores devem ser um array.");
        }
        if(!is_array($anime->studios)){
            throw new Exception("Os studios devem ser um array.");
        }
        if(!is_array($anime->genres)){
            throw new Exception("Os gêneros devem ser um array.");
        }
        if(!is_array($anime->explicit_genres)){
            throw new Exception("Os gêneros explícitos devem ser um array.");
        }
        if(!is_array($anime->themes)){
            throw new Exception("Os temas devem ser um array.");
        }
        if(!is_array($anime->demographics)){
            throw new Exception("As demografias devem ser um array.");
        }
    }
}
?>
