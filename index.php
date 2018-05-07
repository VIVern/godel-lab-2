<?php

  // require_once 'config/config.php';

  require_once 'classes/Film.php';
  require_once 'classes/DataActions.php';

  $app = new DataActions('localhost', 'root', '123', 'lab2');
  $app->connectToDatabase();

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
      $app->showData();
    } elseif (isset($_POST['days']) === true) {
      $app->showData($_POST['days']);
    }
  } else {
    $app->showData();
  }
