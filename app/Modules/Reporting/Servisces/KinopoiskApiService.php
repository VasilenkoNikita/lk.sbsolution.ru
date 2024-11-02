<?php

namespace App\Services\KinopoiskAPI;

use App\Models\Movie;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

final class KinopoiskApiService
{
    private $kpToken = 'MHMC65C-7KXMDK5-GM7KQ1F-V1V6MBV';

    /**
     * @param String $name
     * @return Collection
     */
    public function searchMovieByName(string $name): Collection
    {
        $movieListResult = "https://api.kinopoisk.dev/v1/movie?selectFields=names.name&selectFields=rating.kp&selectFields=id&electFields=genres.name&selectFields=movieLength&selectFields=shortDescription&selectFields=poster.url&name=$name&sortField=rating.kp&sortType=-1&type=movie&type=tv-series&type=cartoon&type=anime&type=animated-series&token=$this->kpToken";
        $response = Http::get($movieListResult);

        return collect(json_decode($response->body(), true));
    }

    /**
     * @param int $movie_id
     * @return Collection
     */
    public function searchMovieByID(int $movie_id): Collection
    {
        $movieListResult = "https://api.kinopoisk.dev/v1/movie/$movie_id?token=$this->kpToken";
        $response = Http::get($movieListResult);

        return collect(json_decode($response->body(), true));
    }
}
