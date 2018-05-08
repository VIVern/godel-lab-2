<?php
  include_once 'FilmData.php';
  include_once 'DataApi.php';

  class App
  {
    public $db;
    public $api;

    public function setDatabase($db_location, $db_user, $db_pass, $db_name, $API_token)
    {
      $this->db = new FilmData($db_location, $db_user, $db_pass, $db_name, $API_token);
    }

    public function setApi()
    {
      $this->api = new DataApi();
    }
  }
