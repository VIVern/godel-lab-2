<?php

  require_once 'config/config.php';

  require_once 'classes/Film.php';

  // inserts data into data base;
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

  //creating array of objects to show them on template
  function showData($db_con)
  {
    $films=[];
    $dayFilter= 14;

    //select from database
    $querry = "SELECT * FROM films";
    $req = mysqli_query($db_con,$querry);

    while ($result = mysqli_fetch_array($req)) {
      array_push($films, new Film ($result['title'], $result['titleOriginal'], $result['poster'], $result['overview'], $result['releaseDate'], $result['genres']));
    }
    include_once 'view/films.phtml';
  }

  if (isset($argv) === true || $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($argv) === true || $_POST['mod'] === 'Query') {

      include_once 'request.php';

      echo "geting data from tmdb";

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
      cacheData($films, $db_con);

      if (isset($argv) === true) {
        echo "data was updated succesfuly \n";
      } else {
        include_once 'view/succes.html';
      }
    } elseif ($_POST['mod'] === 'List') {
      showData($db_con);
    }
  } else {
    showData($db_con);
  }
