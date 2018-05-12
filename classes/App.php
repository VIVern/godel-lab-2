<?php
  include_once 'Mysql.php';
  include_once 'Request.php';
  include_once 'View.php';
  include_once 'FilmData.php';
  include_once 'ShowData.php';

  class App
  {
    public $db;
    public $request;
    public $view;
    public $film;
    public $show;

    public function __construct($param1)
    {
      $this->setView();
      $this->setFilm();
      $this->setShow();
    }

    public function setDatabase($db_location, $db_user, $db_pass, $db_name)
    {
      $this->db = new Mysql($db_location, $db_user, $db_pass, $db_name);
    }

    private function setView()
    {
      $this->view = new View();
    }

    private function setFilm()
    {
      $this->film = new FilmData();
    }

    private function setShow()
    {
      $this->show = new ShowData();
    }
  }
