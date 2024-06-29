<?php

namespace App\Validators;

use App\Models\User;
use App\Models\Anime;
use App\Models\Titles;
use App\Models\TitlesSynonyms;
use Exception;

class Validator {
    public static function validateTitle(Titles $title): void
    {
        if (empty($title->title)) {
            throw new Exception("O Título não pode ser nulo.");
        }
        if (empty($title->anime_id)) {
            throw new Exception("O Id do Anime não pode ser nulo.");
        }
        if (empty($title->type)) {
            throw new Exception("O Tipo não pode ser nulo.");
        }
    }

    public static function validateTitleSynonyms(Titles $title): void
    {
        if (empty($title->title)) {
            throw new Exception("O Título não pode ser nulo.");
        }
        if (empty($title->anime_id)) {
            throw new Exception("O Id do Anime não pode ser nulo.");
        }
    }
    public static function validateUser(User $user): void
    {
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

    public static function validateAnime(Anime $anime): void
    {

        if (empty($anime->title)) {
            throw new Exception("O nome padrão não pode ser nulo.");
        }
//        if(!is_array($anime->titles)){
//            throw new Exception("Os títulos devem ser um array.");
//        }
//        if(!is_array($anime->title_synonyms)){
//            throw new Exception("Os sinônimos de titulos devem ser um array.");
//        }
//        if(!is_array($anime->producers)){
//            throw new Exception("Os produtores devem ser um array.");
//        }
//        if(!is_array($anime->licensors)){
//            throw new Exception("Os licenciadores devem ser um array.");
//        }
//        if(!is_array($anime->studios)){
//            throw new Exception("Os studios devem ser um array.");
//        }
//        if(!is_array($anime->genres)){
//            throw new Exception("Os gêneros devem ser um array.");
//        }
//        if(!is_array($anime->explicit_genres)){
//            throw new Exception("Os gêneros explícitos devem ser um array.");
//        }
//        if(!is_array($anime->themes)){
//            throw new Exception("Os temas devem ser um array.");
//        }
//        if(!is_array($anime->demographics)){
//            throw new Exception("As demografias devem ser um array.");
//        }
//        if(!is_array($anime->relations)){
//            throw new Exception("As relações devem ser um array.");
//        }
//        if(!is_array($anime->external)){
//            throw new Exception("As informações externas devem ser um array.");
//        }
//        if(!is_array($anime->streaming)){
//            throw new Exception("As informações de streaming devem ser um array.");
//        }
    }
}
?>
