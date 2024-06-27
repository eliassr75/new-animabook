<?php

namespace App\Models;

use Exception;
use App\Validators\Validator;
use Ramsey\Uuid\Uuid;
use App\Controllers\TitlesController;
use App\Controllers\TitlesSynonymsController;
use App\Controllers\ProducersController;
use App\Controllers\LicensorsController;
use App\Controllers\StudiosController;
use App\Controllers\GenresController;
use App\Controllers\ExplicitGenresController;
use App\Controllers\ThemesController;
use App\Controllers\DemographicsController;
use App\Controllers\RelationsController;
use App\Controllers\ExternalController;
use App\Controllers\StreamingController;
use App\Controllers\CharactersController;
use App\Controllers\StaffController;
use App\Controllers\EpisodesController;
use App\Controllers\NewsController;
use App\Controllers\ForumController;
use App\Controllers\VideosController;
use App\Controllers\VideoEpisodesController;
use App\Controllers\PicturesController;
use App\Controllers\StatisticsController;
use App\Controllers\RecomendationsController;
use App\Controllers\ReviewsController;

#[\AllowDynamicProperties]
class Anime extends BaseModel
{
    protected $table = "anime";

    protected $columns = [
        'id',
        'mal_id',
        'url',
        'images',
        'trailer',
        'approved',
        'title',
        'title_english',
        'title_japanese',
        'type',
        'source',
        'episodes_counter',
        'status',
        'airing',
        'aired',
        'duration',
        'rating',
        'score',
        'scored_by',
        'rank',
        'popularity',
        'members',
        'favorites',
        'synopsis',
        'background',
        'season',
        'year',
        'broadcast',
        'theme',
        'updated',
        'created',
        'active'
    ];

    public $allowed_keys = [
        'mal_id',
        'url',
        'images',
        'trailer',
        'approved',
        'title',
        'title_english',
        'title_japanese',
        'type',
        'source',
        'episodes_counter',
        'status',
        'airing',
        'aired',
        'duration',
        'rating',
        'score',
        'scored_by',
        'rank',
        'popularity',
        'members',
        'favorites',
        'synopsis',
        'background',
        'season',
        'year',
        'broadcast',
        'theme',
    ];

    public $id;
    public $mal_id;
    public $url;
    public $images;
    public $trailer;
    public $approved;
    public $title;
    public $title_english;
    public $title_japanese;
    public $type;
    public $source;
    public $episodes_counter;
    public $status;
    public $airing;
    public $aired;
    public $duration;
    public $rating;
    public $score;
    public $scored_by;
    public $rank;
    public $popularity;
    public $members;
    public $favorites;
    public $synopsis;
    public $background;
    public $season;
    public $year;
    public $broadcast;
    public $theme;

    public $updated;
    public $created;
    public $active;
    public $titles;
    public $title_synonyms;
    public $producers;
    public $licensors;
    public $studios;
    public $genres;
    public $explicit_genres;
    public $themes;
    public $demographics;
    public $relations;
    public $external;
    public $streaming;
    public $characters;
    public $staff;
    public $episodes;
    public $news;
    public $forum;
    public $videos;
    public $video_episodes;
    public $pictures;
    public $statistics;
    public $recomendations;
    public $reviews;

    public $token;

    public function sub_construct(): void
    {
        $this->titles = new TitlesController();
        $this->title_synonyms = new TitlesSynonymsController();
        $this->producers = new ProducersController();
        $this->licensors = new LicensorsController();
        $this->studios = new StudiosController();
        $this->genres = new GenresController();
        $this->explicit_genres = new ExplicitGenresController();
        $this->themes = new ThemesController();
        $this->demographics = new DemographicsController();
        $this->relations = new RelationsController();
        $this->external = new ExternalController();
        $this->streaming = new StreamingController();
        $this->characters = new CharactersController();
        $this->staff = new StaffController();
        $this->episodes = new EpisodesController();
        $this->news = new NewsController();
        $this->forum = new ForumController();
        $this->videos = new VideosController();
        $this->video_episodes = new VideoEpisodesController();
        $this->pictures = new PicturesController();
        $this->statistics = new StatisticsController();
        $this->recomendations = new RecomendationsController();
        $this->reviews = new ReviewsController();
    }

    public function validate()
    {
        if (empty($this->token)) {
            $this->token = Uuid::uuid4();
        }

        Validator::validateAnime($this);
    }

    public function save()
    {
        $this->validate();
        parent::save();
    }
}
