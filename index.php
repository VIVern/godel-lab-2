<?php

  require_once 'config/config.php';

  require_once 'classes/Film.php';

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

      include_once 'request.php';

      $films = [];

      // clear uploads folder
      if (file_exists('./uploads/') === true) {
        foreach (glob('./uploads/*') as $file) {
          unlink($file);
        }
      }

      for ($i = 0; $i < count($result); $i++) {
        $url = 'https://image.tmdb.org/t/p/w200/' . $result[$i]['poster_path'];
        $path = './uploads/film_'. $i .'.jpg';
        file_put_contents($path, file_get_contents($url));

        array_push($films, new Film($result[$i]['title'], $result[$i]['original_title'], $path, $result[$i]['overview'], $result[$i]['release_date'], $result[$i]['genre_ids']));
        $films[$i]->getGenres($genre);

      }

      //push to database
      cacheData($films, $db_con);

      include_once 'view/succes.html';

    } elseif ($_POST['mod'] === 'List') {
      $films=[];
      $dayFilter= 14;

      //select from database
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
