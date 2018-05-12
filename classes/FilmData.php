<?php
  include_once 'Film.php';
  include_once 'Request.php';

  class FilmData
  {
    public $films = [];

    public function createFilmUnits($filmsArray)
    {
      $films = [];
      foreach ($filmsArray as $film) {
        array_push($films, new Film (
          $film['id'],
          $film['title'],
          $film['titleOriginal'],
          $film['poster'],
          $film['overview'],
          $film['releaseDate'],
          $film['genres'],
          $film['runtime']
        ));
      }
      return $films;
    }

    public function requestFilms($API_token, $pages = 0, $language = 'ru', $region = 'Ru')
    {
      if ($pages === 0) {
        $page = 1;
      }

      $options = [
        'api_key' => $API_token,
        'language' => $language,
        'page' => $page,
        'region' => $region
      ];

      $url = 'https://api.themoviedb.org/3/movie/now_playing?' . http_build_query($options);

      $request = new Request();
      $request->getData($url);
      $response = $request->response['results'];

      if ( $pages === 0) {
        for($i = 2; $i <= $request->response['total_pages']; $i++)
        {
          $options = [
            'api_key' => $API_token,
            'language' => $language,
            'region' => $region,
            'page' => $i
          ];
          $url = 'https://api.themoviedb.org/3/movie/now_playing?' . http_build_query($options);
          $request->getData($url);
          $response = array_merge($response, $request->response['results']);
        }
      }
      $this->parseFilmsResponse($response);
      $this->requestFilmDetails($this->films);
    }

    private function parseFilmsResponse($filmsArray)
    {
      foreach ($filmsArray as $film)
      {
        array_push($this->films, new Film(
          $film['id'],
          $film['title'],
          $film['original_title'],
          $film['poster_path'],
          $film['overview'],
          $film['release_date']
        ));
      }
    }

    private function requestFilmDetails($filmArray)
    {

      if (file_exists('./uploads/films/') === true) {
        foreach (glob('./uploads/films/*') as $file) {
          unlink($file);
        }
        //Logger::writeMessage("Uploads/shows folder was cleared");
      }

      foreach ($filmArray as $film)
      {
        $options = [
          'api_key' => 'e3c790bdb811cade513e875f4806841d',
          'language' => 'ru',
        ];

        $url = 'https://api.themoviedb.org/3/movie/' . $film->id . '?' . http_build_query($options);
        $request = new Request();
        $request->getData($url);
        $details = $request->response;
        $film->runtime = $details['runtime'];

        foreach ($details['genres'] as $genre)
        {
          $film->genres .=$genre['name'] . ",";
        }

        if (isset($film->poster) === true) {
          $url = 'https://image.tmdb.org/t/p/w200/' . $film->poster;
          $path = './uploads/films/film_' . $film->id . '.jpg';
          $poster = file_get_contents($url);

          // if ($path === false) {
          //   Logger::writeMessage("Failed to download poster from tmdb. Check tmdb server status and request url");
          // } else {
          //   Logger::writeMessage("Poster was received successfully");
          // }

          file_put_contents($path, $poster);
          $film->poster = $path;
        }
      }
    }
  }
