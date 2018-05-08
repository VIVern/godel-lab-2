<?php
  include_once './abstract_classes/Database.php';

  class Mysql extends Database
  {
    private $db_location = '';
    private $db_user = '';
    private $db_pass = '';
    private $db_name = '';
    private $API_token = '';
    protected $db_con;

    function __construct($db_location, $db_user, $db_pass, $db_name, $API_token)
    {
      $this->db_location = $db_location;
      $this->db_user = $db_user;
      $this->db_pass = $db_pass;
      $this->db_name = $db_name;
      $this->API_token = $API_token;
      $this->connect();
    }

    protected function connect()
    {
      // $log = fopen('./logs/log.txt', 'a');

      $this->db_con = mysqli_connect($this->db_location, $this->db_user, $this->db_pass, $this->db_name);
      mysqli_set_charset($this->db_con, 'utf8');
      return $this->db_con;
      // if (!$this->db_con) {
      //   $text = date("Y-m-d H:m:s") . " fail to connect to database. check config.php\n";
      //   fwrite($log, $text);
      //   exit('Warning: check log file for more information');
      // } else {
      //   $text = date("Y-m-d H:i:s") . " connected to database successfully\n";
      //   fwrite($log, $text);
      // }
    }
  }
