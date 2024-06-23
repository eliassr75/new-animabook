<?php

namespace App\Controllers;

use App\Models\Anime;
use GuzzleHttp\Client;

class AnimeController extends BaseController
{

    protected $url = "https://api.jikan.moe/v4/";
    public $query = null;
    public $api = false;

    public function sendRequest($requestData, $method)
    {
        $client = new Client();

        $response = $client->$method("{$this->url}{$this->query}", [
            'headers' => ['Content-Type' => 'application/json'],
            ($method == "get" ? 'query': 'body') => ($method == "get" ? $requestData : json_encode($requestData, true))
        ]);

        try {
            return json_decode($response->getBody());
        }catch (Exception $e){
            return ["error" => $e->getMessage()];
        }
    }

    public function sendResponse($responseData, $statusCode = 200)
    {
        if($this->api){
            http_response_code($statusCode);
            return json_encode($responseData, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        }else{
            return $responseData;
        }

    }

    public function getAllFilters()
    {

        return [
            [
                "type_filter" => "select",
                "filters" => [
                    "name" => "anime_search_query_type",
                    "values" => [
                        ["name" => "tv", "label" => "TV"],
                        ["name" => "tv_special", "label" => "TV Especial"],
                        ["name" => "movie", "label" => "Filme"],
                        ["name" => "ova", "label" => "OVA"],
                        ["name" => "ona", "label" => "ONA"],
                        ["name" => "special", "label" => "Especial"],
                        ["name" => "music", "label" => "Musical"],
                        ["name" => "cm", "label" => "CM"],
                        ["name" => "pv", "label" => "PV"],
                    ]
                ],
                [
                    "name" => "top_anime_filter",
                    "values" => [
                        ["name" => "airing", "label" => "Em Lançamento"],
                        ["name" => "upcoming", "label" => "Em Breve"],
                        ["name" => "bypopularity", "label" => "Mais Populares"],
                        ["name" => "favorite", "label" => "Favoritos"],
                    ]
                ],
                [
                    "name" => "anime_search_query_rating",
                    "values" => [
                        ["name" => "g", "label" => "Todas as Idades"],
                        ["name" => "pg", "label" => "Infantis"],
                        ["name" => "pg13", "label" => "Adolescentes com 13 anos ou mais"],
                        ["name" => "r17", "label" => "Violência e Profanidade"],
                        ["name" => "r", "label" => "Nudez Leve"],
                        ["name" => "rx", "label" => "Hentai"],
                    ]
                ]
            ],
            [
                "type_filter" => "radio",
                "filters" => [
                    "name" => "sfw",
                    "label" => "Exibir conteúdo adulto",
                    "values" => [
                        ["name" =>"Sim", "value" => 0],
                        ["name" =>"Não", "value" => 1]
                    ]
                ]
            ]
            ,
            "page" => [],
            "limit" => []
        ];
    }

    public function getAll($type="tv", $filter="bypopularity", $rating="g", $page=1, $limit=24)
    {

        $parameters = [
            "anime_search_query_type" => $type,
            "top_anime_filter" => $filter,
            "anime_search_query_rating" => $rating,
            "sfw" => isset($_GET["sfw"]) ? intval($_GET["sfw"]) : 1,
            "page" => $page,
            "limit" => $limit
        ];
        $response = $this->sendRequest($parameters, "get");
        if (!empty($response->error)) {
            return $this->sendResponse($response, 500);
        } else {
            return $this->sendResponse($response);
        }
    }

    public function createUser()
    {
//        $data = json_decode(file_get_contents('php://input'), true);
//
//        if (!empty($data['name']) && !empty($data['email']) && !empty($data['password'])) {
//            $user = new User($this->pdo);
//            $user->name = $data['name'];
//            $user->email = $data['email'];
//            $user->password = password_hash($data['password'], PASSWORD_DEFAULT);
//            $user->save();
//
//            header('Content-Type: application/json');
//            echo json_encode(['message' => 'Usuário criado com sucesso']);
//        } else {
//            header('Content-Type: application/json', true, 400);
//            echo json_encode(['message' => 'Dados incompletos']);
//        }
    }

    public function showAllAnimes()
    {
        define('TITLE_PAGE', 'Animes');
        $this->query = "top/anime/";
        $response_animes = $this->getAll();
        $this->render('animes', ['response_animes' => $response_animes, 'filters' => false]);
    }
}
