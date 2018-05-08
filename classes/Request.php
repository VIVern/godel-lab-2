<?php
  include_once 'Film.php';

  class Request
  {
    protected $response;
    protected $genre;
    public $films=[];

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
      $this->parseFilms();
    }

    protected function getFilms($context)
    {
      $query = json_decode(file_get_contents('https://api.themoviedb.org/3/movie/now_playing?api_key=e3c790bdb811cade513e875f4806841d&language=ru&page=1&region=Ru', false, $context), true);
      $countPages = $query['total_pages'];
      $result = $query['results'];

      // for ($i = 2; $i <= $countPages; $i++) {
      //   $query = json_decode(file_get_contents('https://api.themoviedb.org/3/movie/now_playing?api_key=e3c790bdb811cade513e875f4806841d&language=ru&page=' . $i . '&region=Ru', false, $context), true);
      //   $page = $query['results'];
      //   $result = array_merge($result, $page);
      // }

      $this->response = $result;
    }
    protected function getGenre($context)
    {
      $query = json_decode(file_get_contents('https://api.themoviedb.org/3/genre/movie/list?api_key=e3c790bdb811cade513e875f4806841d&language=ru', false, $context), true);
      $this->genre = $query['genres'];
    }

    protected function parseFilms()
    {
      if (file_exists('./uploads/') === true) {
        foreach (glob('./uploads/*') as $file) {
          unlink($file);
        }
      }

      for ($i = 0; $i < count($this->response); $i++) {
        array_push($this->films, new Film ($this->response[$i]['title'], $this->response[$i]['original_title'], $this->response[$i]['poster_path'], $this->response[$i]['overview'], $this->response[$i]['release_date'], $this->response[$i]['genre_ids']));
        $this->films[$i]->setGenres($this->genre);
        $this->films[$i]->getPoster($i);
      }
    }
  }
