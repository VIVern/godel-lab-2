<?php
  include_once  'Database.php';

  class DataActions extends Database
  {
    function cacheData($films)
    {
      $log = fopen('./logs/log.txt', 'a');
      $querry = "DELETE FROM films";
      $req = mysqli_query($this->db_con, $querry);

      if (count($films) === 0) {
        $text = date("Y-m-d H:m:s") . " failed to get data from tmdb. Check request.php file and tmdb server status\n";
        fwrite($log, $text);
        exit('Warning: check log file for more information');
      } else {
        $text = date("Y-m-d H:i:s") . " Data recived successfully\n";
        fwrite($log, $text);
      }

      foreach ($films as $film) {
        $title = $film->title;
        $titleOriginal = $film->titleOriginal;
        $poster = $film->poster;
        $overview = $film->overview;
        $realeseDate = $film->releaseDate;
        $genre = $film->genres;

        $querry = "INSERT INTO films VALUES( NULL, '$title', '$titleOriginal', '$poster', '$overview', '$realeseDate', '$genre')";

        $req = mysqli_query($this->db_con, $querry);

        if ($req === false) {
          $text = date("Y-m-d H:m:s") . " failed to insert data into database. Check query in cacheData(). Chech connection to database\n";
          fwrite($log, $text);
          exit('Warning: check log file for more information');
        } else {
          $text = date("Y-m-d H:i:s") . " data inserted successfully\n";
          fwrite($log, $text);
        }
      }
    }

    function showData($days=7)
    {
      $films = [];
      $dayFilter = $days;

      //select from database
      $querry = "SELECT * FROM films";
      $req = mysqli_query($this->db_con, $querry);

      while ($result = mysqli_fetch_array($req)) {
        array_push($films, new Film ($result['title'], $result['titleOriginal'], $result['poster'], $result['overview'], $result['releaseDate'], $result['genres']));
      }
      include_once './view/films.phtml';
    }

    function getNewData()
    {
      $films=[];

      $options = [
        'http' => [
          'method' => "GET",
          'header' => 'Content-type: application/x-www-form-urlencoded'
        ]
      ];

      $context = stream_context_create($options);

      $query = json_decode(file_get_contents('https://api.themoviedb.org/3/movie/now_playing?api_key=e3c790bdb811cade513e875f4806841d&language=ru&page=1&region=Ru', false, $context), true);
      $countPages = $query['total_pages'];
      $result = $query['results'];

      for ($i = 2; $i <= $countPages; $i++) {
        $query = json_decode(file_get_contents('https://api.themoviedb.org/3/movie/now_playing?api_key=e3c790bdb811cade513e875f4806841d&language=ru&page=' . $i . '&region=Ru', false, $context), true);
        $page = $query['results'];
        $result = array_merge($result, $page);
      }


      $query = json_decode(file_get_contents('https://api.themoviedb.org/3/genre/movie/list?api_key=e3c790bdb811cade513e875f4806841d&language=ru', false, $context), true);
      $genre = $query['genres'];

      // clear uploads folder
      if (file_exists('./uploads/') === true) {
        foreach (glob('./uploads/*') as $file) {
          unlink($file);
        }
      }

      // upload images from tmdb
      for ($i = 0; $i < count($result); $i++) {
        if (isset($result[$i]['poster_path']) === true) {
          $url = 'https://image.tmdb.org/t/p/w200/' . $result[$i]['poster_path'];
          $path = './uploads/film_'. $i .'.jpg';
          file_put_contents($path, file_get_contents($url));
        }

        array_push($films, new Film ($result[$i]['title'], $result[$i]['original_title'], $path, $result[$i]['overview'], $result[$i]['release_date'], $result[$i]['genre_ids']));
        $films[$i]->getGenres($genre);
      }

      return $films;
    }
  }
