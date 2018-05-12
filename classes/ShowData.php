<?php
  include_once 'Unit.php';
  include_once 'Request.php';

  class ShowData
  {
    public $shows = [];

    public function createShowUnits($showsArray)
    {
      $shows = [];
      foreach ($showsArray as $show) {
        array_push($films, new Unit (
          $show['id'],
          $show['name'],
          $show['originalName'],
          $show['poster'],
          $show['overview'],
          $show['releaseDate'],
          $show['genres']
        ));
      }
      return $shows;
    }

    public function requestShows($API_token, $language = 'ru')
    {

      $options = [
        'api_key' => $API_token,
        'language' => $language,
      ];

      $url = 'https://api.themoviedb.org/3/tv/popular?' . http_build_query($options);

      $request = new Request();
      $request->getData($url);
      $response = $request->response['results'];

      $this->parseShowsResponse($response);
      $this->requestShowDetails($this->shows);
      var_dump($this->shows);
    }

    private function parseShowsResponse($showsArray)
    {
      foreach ($showsArray as $show)
      {
        array_push($this->shows, new Unit (
          $show['id'],
          $show['name'],
          $show['original_name'],
          $show['poster_path'],
          $show['overview'],
          $show['first_air_date']
        ));
      }
    }

    private function requestShowDetails($showsArray)
    {

      if (file_exists('./uploads/shows/') === true) {
        foreach (glob('./uploads/shows/*') as $file) {
          unlink($file);
        }
        //Logger::writeMessage("Uploads/shows folder was cleared");
      }

      foreach ($showsArray as $show)
      {
        $options = [
          'api_key' => 'e3c790bdb811cade513e875f4806841d',
          'language' => 'ru',
        ];

        $url = 'https://api.themoviedb.org/3/tv/' . $show->id . '?' . http_build_query($options);
        $request = new Request();
        $request->getData($url);
        $details = $request->response;

        foreach ($details['genres'] as $genre)
        {
          $show->genres .=$genre['name'] . ",";
        }

        if (isset($show->poster) === true) {
          $url = 'https://image.tmdb.org/t/p/w200/' . $show->poster;
          $path = './uploads/shows/show_' . $show->id . '.jpg';
          $poster = file_get_contents($url);

          // if ($path === false) {
          //   Logger::writeMessage("Failed to download poster from tmdb. Check tmdb server status and request url");
          // } else {
          //   Logger::writeMessage("Poster was received successfully");
          // }

          file_put_contents($path, $poster);
          $show->poster = $path;
        }
      }
    }
  }
