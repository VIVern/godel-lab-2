<?php
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

      // gettin films from tmdb and pushing to data base
      $app->film->requestFilms($API_token);
      $app->db->setData($app->film->films, 'films');

      // gettin shows from tmdb and pushing to data base
      $app->show->requestShows($API_token);
      $app->db->setData($app->show->shows, 'shows');

      if (isset($argv) === true) {
        echo "data was updated succesfuly \n";
      } else {
        //showing success message
        $app->view->showStaticPage('./view/succes.html');
      }
    } elseif ((isset($_POST['mod']) === true && $_POST['mod'] === 'List') || isset($_POST['films']) === true) {
      // show data with default filter (7days)
      $films = $app->film->createFilmUnits($app->db->getData('films'));
      $app->view->showData('./view/films.phtml', $films);
    } elseif (isset($_POST['days']) === true) {
      // show data with days filter value
      $films = $app->film->createFilmUnits($app->db->getData('films'));
      $app->view->showData('./view/films.phtml', $films, $_POST['days']);
    } elseif (isset($_POST['shows']) === true) {
      // showing lists of shows
      $shows = $app->show->createShowUnits($app->db->getData('shows'));
      $app->view->showData('./view/shows.phtml', $shows);
    }
  } else {
    // defualt showing;
    $films = $app->film->createFilmUnits($app->db->getData('films'));
    $app->view->showData('./view/films.phtml', $films);
  }
