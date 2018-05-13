<?php
  include_once 'Unit.php';
  include_once 'Request.php';

  class FilmData
  {
    public $films = [];

    public function createFilmUnits($filmsArray)
    {
      $films = [];
      foreach ($filmsArray as $film) {
        array_push($films, new Unit (
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

      if ($request->response['results'] === 0) {
        Logger::writeMessage("Failed to get data via " . $url . " request");
      } else {
        Logger::writeMessage("Data via " . $url . " recived successfully");
      }

      $response = $request->response['results'];

      if ( $pages === 0) {
        for ($i = 2; $i <= $request->response['total_pages']; $i++) {
          $options = [
            'api_key' => $API_token,
            'language' => $language,
            'region' => $region,
            'page' => $i
          ];

          $url = 'https://api.themoviedb.org/3/movie/now_playing?' . http_build_query($options);
          $request->getData($url);

          if ($request->response['results'] === 0) {
            Logger::writeMessage("Failed to get data via " . $url . " request");
          } else {
            Logger::writeMessage("Data via " . $url . " recived successfully");
          }

          $response = array_merge($response, $request->response['results']);
        }
      }

      $this->parseFilmsResponse($response);
      $this->requestFilmDetails($this->films, $API_token, $language);
    }

    private function parseFilmsResponse($filmsArray)
    {
      foreach ($filmsArray as $film) {
        array_push($this->films, new Unit (
          $film['id'],
          $film['title'],
          $film['original_title'],
          $film['poster_path'],
          $film['overview'],
          $film['release_date']
        ));
      }
    }

    private function requestFilmDetails($filmArray, $API_token, $language)
    {
      if (file_exists('./uploads/films/') === true) {
        foreach (glob('./uploads/films/*') as $file) {
          unlink($file);
        }
        Logger::writeMessage("Uploads/films folder was cleared");
      }

      foreach ($filmArray as $film) {
        $options = [
          'api_key' => $API_token,
          'language' => $language
        ];

        $url = 'https://api.themoviedb.org/3/movie/' . $film->id . '?' . http_build_query($options);
        $request = new Request();
        $request->getData($url);

        if (isset($request->response['status_code']) === true) {
          Logger::writeMessage("Failed to get film details via " . $url . " request");
        } else {
          Logger::writeMessage("Film details via " . $url . " recived successfully");
        }

        $details = $request->response;

        if ($details['runtime'] !== null) {
          $film->runtime = $details['runtime'];
        } else {
          $film->runtime = 0;
        }

        foreach ($details['genres'] as $genre) {
          $film->genres .=$genre['name'] . ",";
        }

        if (isset($film->poster) === true) {
          $url = 'https://image.tmdb.org/t/p/w200/' . $film->poster;
          $path = './uploads/films/film_' . $film->id . '.jpg';
          $poster = file_get_contents($url);

          if ($path === false) {
            Logger::writeMessage("Failed to download poster from tmdb. Check tmdb server status and request url");
          } else {
            Logger::writeMessage("Poster was received successfully");
          }

          file_put_contents($path, $poster);
          $film->poster = $path;
        } else {
          $film->poster = "there is no poster";
        }
      }
    }
  }
