<?php
  require_once 'config/config.php';
  require_once 'classes/Film.php';
  require_once 'classes/DataActions.php';

  $app = new DataActions($db_location, $db_user, $db_pass, $db_name, $API_token);

  if (isset($argv) === true || $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($argv) === true || $_POST['mod'] === 'Query') {

      if (isset($argv) === true) {
        echo "geting data from tmdb \n";
      }

      // gettin data from tmdb
      $films = $app->getNewData();

      //push to database
      $app->cacheData($films);

      if (isset($argv) === true) {
        echo "data was updated succesfuly \n";
      } else {
        include_once 'view/succes.html';
      }
    } elseif ($_POST['mod'] === 'List') {
      // show data with default filter (7days)
      $app->showData();
    } elseif (isset($_POST['days']) === true) {
      // show data with new days filter value
      $app->showData($_POST['days']);
    }
  } else {
    // defualt showing;
    $app->showData();
  }
