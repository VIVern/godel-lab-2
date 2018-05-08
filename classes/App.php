<?php
  include_once 'FilmData.php';
  include_once 'Request.php';

  class App
  {
    public $db;
    public $request;

    function __construct()
    {
      $this->setApi();
    }

    public function setDatabase($db_location, $db_user, $db_pass, $db_name, $API_token)
    {
      $this->db = new FilmData($db_location, $db_user, $db_pass, $db_name, $API_token);
    }

    public function setApi()
    {
      $this->request = new Request();
    }
  }
