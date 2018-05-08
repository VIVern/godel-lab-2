<?php
  ini_set('error_reporting', E_ALL);
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);

  require_once 'config/config.php';
  require_once 'classes/App.php';

  $app = new App();
  $app->setDatabase($db_location, $db_user, $db_pass, $db_name, $API_token);

  if (isset($argv) === true || $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($argv) === true || (isset($_POST['mod']) === true && $_POST['mod'] === 'Query')) {

      if (isset($argv) === true) {
        echo "geting data from tmdb \n";
      }

      // gettin data from tmdb
      $app->request->getData();

      //push to database
      $app->db->setData($app->request->films);

      if (isset($argv) === true) {
        echo "data was updated succesfuly \n";
      } else {
        $app->view->showStaticPage('./view/succes.html');
      }
    } elseif (isset($_POST['mod']) === true && $_POST['mod'] === 'List') {
      // show data with default filter (7days)
      $app->db->getData();
      $app->view->showData($app->db->films,'./view/films.phtml');
    } elseif (isset($_POST['days']) === true) {
      // show data with new days filter value
      $app->db->getData();
      $app->view->showData($app->db->films,'./view/films.phtml',$_POST['days']);
    }
  } else {
    // defualt showing;
    $app->db->getData();
    $app->view->showData($app->db->films,'./view/films.phtml');
  }
