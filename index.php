<?php

  require_once 'config/config.php';

  $options = [
    'http' => [
      'method' => "GET",
      'header' => 'Content-type: application/x-www-form-urlencoded'
    ]
  ];

  $context = stream_context_create($options);

  $query = json_decode(file_get_contents('https://api.themoviedb.org/3/movie/now_playing?api_key=e3c790bdb811cade513e875f4806841d&language=ru', false, $context), true);
  $result = $query['results'];

  $query = json_decode(file_get_contents('https://api.themoviedb.org/3/genre/movie/list?api_key=e3c790bdb811cade513e875f4806841d&language=ru', false, $context), true);
  $genre = $query['genres'];


  class Film
  {
    public $title;
    public $titleOriginal;
    public $poster;
    public $overview;
    public $releaseDate;
    //public $runtime; there is no such option;
    public $genresId;
    public $genresList = [];

    public function __construct($param1, $param2, $param3, $param4, $param5, $param6)
    {
      $this->title = $param1;
      $this->titleOriginal = $param2;
      $this->poster = $param3;
      $this->overview = $param4;
      $this->releaseDate = $param5;
      $this->genresId = $param6;
    }

    public function getGenres($genreArray)
    {
      echo "run method";
      echo "<br>";
      foreach ($this->genresId as $id) {
        foreach ($genreArray as $val) {
          if($val['id'] === $id) {
            array_push($this->genresList, $val['name']);
          }
        }
      }
    }
  }

  $films = [];

  for ($i = 0; $i < count($result); $i++) {
    array_push($films, new Film($result[$i]['title'], $result[$i]['original_title'], $result[$i]['poster_path'], $result[$i]['overview'], $result[$i]['release_date'], $result[$i]['genre_ids']));
    $films[$i]->getGenres($genre);
  }

  print_r($films);


  // request to data base block;
  foreach ($films as $film) {
    print_r($films[0]->overview);

    $title = $film->title;
    $titleOriginal = $film->titleOriginal;
    $poster = $film->poster;
    $overview = $film->overview;
    $realeseDate = $film->releaseDate;
    $genre = $film->genresList;


    $querry = "INSERT INTO films VALUES( NULL, '$title', '$titleOriginal' ,'$poster' , '$overview', '$realeseDate', '$genre')";

    $req=mysqli_query($db_con,$querry);
  }
