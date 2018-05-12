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
        array_push($films, new Film ($film['id'], $film['title'], $film['titleOriginal'], $film['poster'], $film['overview'], $film['releaseDate'], $film['genres'], $film['runtime']));
      }
      return $films;
    }
    //
    // public function parseFilms($films, $genres)
    // {
    //   $this->clearUploads();
    //
    //   for ($i = 0; $i < count($films); $i++) {
    //     array_push($this->films, new Film ($films[$i]['id'], $films[$i]['title'], $films[$i]['original_title'], $films[$i]['poster_path'], $films[$i]['overview'], $films[$i]['release_date'], $films[$i]['genre_ids']));
    //     $this->films[$i]->setGenres($genres);
    //     $this->films[$i]->getPoster($i);
    //     $this->films[$i]->getRuntime();
    //   }
    // }
    //
    // private function clearUploads()
    // {
    //   if (file_exists('./uploads/films/') === true) {
    //     foreach (glob('./uploads/films/*') as $file) {
    //       unlink($file);
    //     }
    //     Logger::writeMessage("Uploads/films folder was cleared");
    //   }
    // }

    public function requestFilms()
    {
      $options = [
        'api_key' => 'e3c790bdb811cade513e875f4806841d',
        'language' => 'ru',
        'page' => '1'
      ];

      $url = 'https://api.themoviedb.org/3/movie/now_playing?' . http_build_query($options);

      $request = new Request();
      $request->getData($url);

      $this->parseFilmsResponse($request->response);
      $this->requestFilmDetails($this->films);
      var_dump($this->films);
    }

    private function parseFilmsResponse($data)
    {
      $filmsArray = $data['results'];

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
      }
    }
  }
