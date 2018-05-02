<?php

  require_once 'config/config.php';

  $options = array(
    'http'=>array(
      'method' => "GET",
      'header' => 'Content-type: application/x-www-form-urlencoded'
    )
  );

  $context = stream_context_create($options);

  $query = json_decode(file_get_contents('https://api.themoviedb.org/3/movie/now_playing?api_key=e3c790bdb811cade513e875f4806841d&language=ru', false, $context), true);
  $result = $query['results'];

  class Film
  {
    public $title;
    public $titleOriginal;
    public $poster;
    public $overview;
    public $releaseDate;
    //public $runtime;
    public $genresId;

    public function __construct($param1, $param2, $param3, $param4, $param5, $param6)
    {
      $this->title = $param1;
      $this->titleOriginal = $param2;
      $this->poster = $param3;
      $this->overview = $param4;
      $this->releaseDate = $param5;
      $this->genersId = $param6;
    }

    //protected $genresList = [];

    // protected function getGeners($genersID)
    // {
    //   $options = array(
    //     'http'=>array(
    //       'method' => "GET",
    //       'header' => 'Content-type: application/x-www-form-urlencoded'
    //     )
    //   );
    //
    //   $context = stream_context_create($options);
    //
    //   $query = json_decode(file_get_contents('https://api.themoviedb.org/3/genre/movie/list?api_key=e3c790bdb811cade513e875f4806841d&language=ru', false, $context), true);
    //
    //   for ($i = 0; $i < count($genersID); $i++) {
    //     array_push($this->$genresList, $genersID[$i]);
    //   }
    // }
  }

  $films = [];

  for ($i = 0; $i < count($result); $i++) {
    array_push($films, new Film($result[$i]['title'], $result[$i]['original_title'], $result[$i]['poster_path'], $result[$i]['overview'], $result[$i]['release_date'], $result[$i]['genre_ids']));
  }

  print_r($films[0]->overview);

  $title = $films[0]->title;
  $titleOriginal = $films[0]->titleOriginal;
  $poster = $films[0]->poster;
  $overview = $films[0]->overview;
  $realeseDate = $films[0]->releaseDate;
  $gener = $films[0]->genersId;


  $querry = "INSERT INTO films VALUES( NULL, '$title', '$titleOriginal' ,'$poster' , '$overview', '$realeseDate', '$gener')";

  $req=mysqli_query($db_con,$querry);
