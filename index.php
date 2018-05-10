<?php
  ini_set('error_reporting', E_ALL);
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);

  require_once 'config/config.php';
  require_once 'classes/App.php';
  require_once 'classes/Logger.php';

  $app = new App($API_token);
  $app->setDatabase($db_location, $db_user, $db_pass, $db_name);

  if (isset($argv) === true || $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($argv) === true || (isset($_POST['mod']) === true && $_POST['mod'] === 'Query')) {

      if (isset($argv) === true) {
        echo "geting data from tmdb \n";
      }

      // gettin data from tmdb
      $app->request->getData();
      $app->film->parseFilms($app->request->response,$app->request->genre);

      //push to database
      $app->db->setData($app->film->films, 'films');

      $app->request->getShows();
      $app->show->parseShows($app->request->response,$app->request->genre);

      $app->db->setData($app->show->shows, 'shows');

      if (isset($argv) === true) {
        echo "data was updated succesfuly \n";
      } else {
        $app->view->showStaticPage('./view/succes.html');
      }
    } elseif ((isset($_POST['mod']) === true && $_POST['mod'] === 'List') || isset($_POST['films']) === true) {
      // show data with default filter (7days)
      $films = $app->film->createFilmUnits($app->db->getData('films'));
      $app->view->showData($films,'./view/films.phtml');
    } elseif (isset($_POST['days']) === true) {
      // show data with new days filter value
      $films = $app->film->createFilmUnits($app->db->getData('films'));
      $app->view->showData($films, './view/films.phtml', $_POST['days']);
    } elseif (isset($_POST['shows']) === true) {
      // showing lists of shows
      $shows = $app->show->createShowUnits($app->db->getData('shows'));
      $app->view->showData($shows, './view/shows.phtml');
    }
  } else {
    // defualt showing;
    $films = $app->film->createFilmUnits($app->db->getData('films'));
    $app->view->showData($films, './view/films.phtml');
  }
