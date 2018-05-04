<?php

  require_once 'config/config.php';

  require_once 'classes/Film.php';

  // inserts data into data base;
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

  //creating array of objects to show them on template
  function showData($db_con, $days=7)
  {
    $films = [];
    $dayFilter = $days;

    //select from database
    $querry = "SELECT * FROM films";
    $req = mysqli_query($db_con, $querry);

    while ($result = mysqli_fetch_array($req)) {
      array_push($films, new Film ($result['title'], $result['titleOriginal'], $result['poster'], $result['overview'], $result['releaseDate'], $result['genres']));
    }
    include_once 'view/films.phtml';
  }

  if (isset($argv) === true || $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($argv) === true || $_POST['mod'] === 'Query') {

      if (isset($argv) === true) {
        echo "geting data from tmdb \n";
      }

      include_once 'request.php';

      $films = [];

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

      //push to database
      cacheData($films, $db_con, $log);

      if (isset($argv) === true) {
        echo "data was updated succesfuly \n";
      } else {
        include_once 'view/succes.html';
      }
    } elseif ($_POST['mod'] === 'List') {
      showData($db_con);
    } elseif (isset($_POST['days']) === true) {
      showData($db_con, $_POST['days']);
    }
  } else {
    showData($db_con);
  }
