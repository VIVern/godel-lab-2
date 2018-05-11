<?php
  include_once 'Film.php';

  class Request
  {
    public $response;
    public $genre;
    protected $API_token;

    function __construct($param1)
    {
      $this->API_token = $param1;
    }

    public function getData()
    {
      $options = [
        'http' => [
          'method' => "GET",
          'header' => 'Content-type: application/x-www-form-urlencoded'
        ]
      ];

      $context = stream_context_create($options);
      $this->getFilms($context);
      $this->getGenre($context);
    }

    protected function getFilms($context)
    {
      $query = json_decode(file_get_contents('https://api.themoviedb.org/3/movie/now_playing?api_key=' . $this->API_token . '&language=ru&page=1&region=Ru', false, $context), true);
      $countPages = $query['total_pages'];
      $result = $query['results'];

      for ($i = 2; $i <= $countPages; $i++) {
        $query = json_decode(file_get_contents('https://api.themoviedb.org/3/movie/now_playing?api_key=' . $this->API_token . '&language=ru&page=' . $i . '&region=Ru', false, $context), true);
        $page = $query['results'];
        $result = array_merge($result, $page);
      }

      if (count($result) === 0) {
        Logger::writeMessage("Failed to get data from tmdb. Check tmdb server status and request url");
        exit("Warning: check log file for more information\n");
      } else {
        Logger::writeMessage("Data was received successfully");
      }

      $this->response = $result;
    }
    protected function getGenre($context)
    {
      $query = json_decode(file_get_contents('https://api.themoviedb.org/3/genre/movie/list?api_key=' . $this->API_token . '&language=ru', false, $context), true);
      $this->genre = $query['genres'];

      if (count($query['genres']) === 0) {
        Logger::writeMessage("Failed to get data from tmdb. Check tmdb server status and request url");
        exit("Warning: check log file for more information\n");
      } else {
        Logger::writeMessage("Data was received successfully");
      }
    }

    public function getShows()
    {
      $options = [
        'http' => [
          'method' => "GET",
          'header' => 'Content-type: application/x-www-form-urlencoded'
        ]
      ];

      $context = stream_context_create($options);
      $query = json_decode(file_get_contents('https://api.themoviedb.org/3/tv/popular?api_key=' . $this->API_token . '&language=ru&page=1', false, $context), true);
      $result = $query['results'];

      if (count($result) === 0) {
        Logger::writeMessage("Failed to get data from tmdb. Check tmdb server status and request url");
        exit("Warning: check log file for more information\n");
      } else {
        Logger::writeMessage("Data was received successfully");
      }

      $this->response = $result;
      $this->getGenre($context);
    }
  }
