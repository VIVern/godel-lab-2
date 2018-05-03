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
    //public $runtime;                                                                  //there is no such option in json response;
    public $genresId;
    public $genres;

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
      $genresList = [];
      foreach ($this->genresId as $id) {
        foreach ($genreArray as $val) {
          if($val['id'] === $id) {
            array_push($genresList, $val['name']);
          }
        }
      }
      $this->genres = implode(',',$genresList);
    }
  }

  // working with dara base data base;
  function cacheData($films, $db_con)
  {
    $querry = "DELETE FROM films";

    $req = mysqli_query($db_con,$querry);

    foreach ($films as $film) {
      $title = $film->title;
      $titleOriginal = $film->titleOriginal;
      $poster = $film->poster;
      $overview = $film->overview;
      $realeseDate = $film->releaseDate;
      $genre = $film->genres;

      $querry = "INSERT INTO films VALUES( NULL, '$title', '$titleOriginal' ,'$poster' , '$overview', '$realeseDate', '$genre')";

      $req = mysqli_query($db_con,$querry);
    }
  }

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['mod'] === 'Query') {
      $films = [];

      if (file_exists('./uploads/') === true) {
        foreach (glob('./uploads/*') as $file) {
          unlink($file);
        }
      }

      for ($i = 0; $i < count($result); $i++) {

        $url = 'https://image.tmdb.org/t/p/w200/' . $result[$i]['poster_path'];
        $path = './uploads/film_'. $i .'.png';
        file_put_contents($path, file_get_contents($url));

        array_push($films, new Film($result[$i]['title'], $result[$i]['original_title'], $path, $result[$i]['overview'], $result[$i]['release_date'], $result[$i]['genre_ids']));
        $films[$i]->getGenres($genre);

      }

      cacheData($films, $db_con);

      include_once 'view/succes.html';

    } elseif ($_POST['mod'] === 'List') {
      $films=[];

      $querry = "SELECT * FROM films";
      $req = mysqli_query($db_con,$querry);

      while ($result = mysqli_fetch_array($req)) {
        array_push($films, new Film($result['title'], $result['titleOriginal'], $result['poster'], $result['overview'], $result['releaseDate'], $result['genres']));
      }

      include_once 'view/films.phtml';
    }

  } else {
    include_once 'view/mod.html';
  }
