<?php
  include_once 'FilmData.php';
  include_once 'Request.php';
  include_once 'View.php';

  class App
  {
    public $db;
    public $request;
    public $view;

    function __construct()
    {
      $this->setRequest();
      $this->setView();
    }

    public function setDatabase($db_location, $db_user, $db_pass, $db_name, $API_token)
    {
      $this->db = new FilmData($db_location, $db_user, $db_pass, $db_name, $API_token);
    }

    private function setRequest()
    {
      $this->request = new Request();
    }

    private function setView()
    {
      $this->view = new View();
    }
  }
