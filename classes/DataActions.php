<?php
  include_once  'Database.php';

  class DataActions extends Database
  {
    function cacheData($films, $db_con, $log)
    {
      $querry = "DELETE FROM films";

      $req = mysqli_query($db_con, $querry);

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

        $req = mysqli_query($db_con, $querry);

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
  }
