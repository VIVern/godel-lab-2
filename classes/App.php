<?php
  include_once 'Mysql.php';
  include_once 'Request.php';
  include_once 'View.php';
  include_once 'FilmData.php';

  class App
  {
    public $db;
    public $request;
    public $view;
    public $film;

    function __construct()
    {
      $this->setRequest();
      $this->setView();
      $this->setFilm();
    }

    public function setDatabase($db_location, $db_user, $db_pass, $db_name, $API_token)
    {
      $this->db = new Mysql($db_location, $db_user, $db_pass, $db_name, $API_token);
    }

    private function setRequest()
    {
      $this->request = new Request();
    }

    private function setView()
    {
      $this->view = new View();
    }

    private function setFilm()
    {
      $this->film = new FilmData();
    }
  }
