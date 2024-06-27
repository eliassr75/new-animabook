<?php

namespace App\Controllers;

use App\Models\Anime;
use Statickidz\GoogleTranslate;
use stdClass;

class AnimeController extends BaseController
{

    protected $url = "https://api.jikan.moe/v4/";
    public $query = null;


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

    public function getAnimeById($animeId) {
        echo "Anime ID: " . htmlspecialchars($animeId, ENT_QUOTES, 'UTF-8');
    }
    public function getAllAnimes()
    {
        $functions = new FunctionController();
        $animes = Anime::all($this->pdo);
        echo '<pre>';
        print_r($animes);
        echo '</pre>';
    }

    public function getAll($type="tv", $filter="bypopularity", $rating="g", $page=1, $limit=24)
    {
        $functions = new FunctionController();
        $parameters = [
            "anime_search_query_type" => $type,
            "top_anime_filter" => $filter,
            "anime_search_query_rating" => $rating,
            "sfw" => isset($_GET["sfw"]) ? intval($_GET["sfw"]) : 1,
            "page" => $page,
            "limit" => $limit
        ];

        $response = $functions->sendRequest($parameters, "{$this->url}{$this->query}", "get");
        if (!empty($response->error)) {
            return $functions->sendResponse($response, 500);
        } else {
            return $functions->sendResponse($response);
        }
    }

    public function createAnime($malId)
    {
        $this->query = "anime/$malId/full/";
        $functions = new FunctionController();
        $functions->api = true;

        $anime = Anime::where($this->pdo, ['mal_id' => $malId]);
        if ($anime) {
            $functions->sendResponse(["message" => "Anime already exists"], 400);
        } else {
            $response = $functions->sendRequest([], "{$this->url}{$this->query}", "get");

            $anime = new Anime($this->pdo);
            foreach ($anime->allowed_keys as $key) {

                if ($key == "episodes_counter"){
                    $anime->$key = $response->data->episodes;
                }else{
                    switch (gettype($response->data->$key)) {
                        case 'boolean':
                            $anime->$key = (int)$response->data->$key;
                            break;
                        case 'object':
                            $anime->$key = json_encode($response->data->$key, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                            break;
                        default:

                            break;
                    }
                }
            }

            $source = 'en';
            $target = 'pt-br';
            $translator = new GoogleTranslate();

            try {
                if(!empty($anime->background)){
                    $anime->background = str_replace("\n\n", "<br>", $translator->translate($source, $target, $anime->background));
                }
                if(!empty($anime->synopsis)) {
                    $anime->synopsis = str_replace("\n\n", "<br>", $translator->translate($source, $target, $anime->synopsis));
                }
            } catch (Exception $e) {

            }

            $anime->save();

            echo '<pre>';
            print_r($anime);
            echo '</pre>';

        }

    }

    public function updateAnime($malId)
    {
        $this->query = "anime/$malId/full/";
        $functions = new FunctionController();
        $functions->api = true;

        $animeModel = new Anime($this->pdo);
        $anime = $animeModel->where('mal_id', '=', $malId)->get();
        if ($anime) {
            $response = $functions->sendRequest([], "{$this->url}{$this->query}", "get");

            $keys = new Anime($this->pdo);
            $stmt = [];
            foreach ($keys->allowed_keys as $key) {

                /*
                 *
                 *
                 * objeto.prop.etc.....
                 * objeto['prop']......
                 *
                 * $objeto->prop->etc......
                 * $objeto->variavel
                 *
                 *
                 * */

                if ($key == "episodes_counter"){
                    $stmt[$key] = $response->data->episodes;
                }else{
                    switch (gettype($response->data->$key)) {
                        case 'boolean':
                            $stmt[$key] = (int)$response->data->$key;
                            break;
                        case 'object':
                            $stmt[$key] = json_encode($response->data->$key, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                            break;
                        default:
                            $stmt[$key] = $response->data->$key;
                            break;
                    }
                }
            }

            $source = 'en';
            $target = 'pt-br';
            $translator = new GoogleTranslate();

            try {
                if(!empty($response->data->background)){
                    $stmt['background'] = str_replace("\n\n", "<br>", $translator->translate($source, $target, $anime->background));
                }
                if(!empty($response->data->synopsis)) {
                    $stmt['synopsis'] = str_replace("\n\n", "<br>", $translator->translate($source, $target, $anime->synopsis));
                }
            } catch (Exception $e) {

            }

            $animeModel->where('mal_id', '=', $malId)->update($stmt);

            echo '<pre>';
            print_r($animeModel->where('mal_id', '=', $malId)->get());
            echo '</pre>';

        } else {
            $functions->sendResponse(["message" => "Anime not founded"], 404);

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
