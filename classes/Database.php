<?php
  class Database
  {
    public $db_location = 'localhost';
    public $db_user = 'root';
    public $db_pass = '123';
    public $db_name = 'lab2';

    public $db_con;

    function __construct($db_location, $db_user, $db_pass, $db_name)
    {
      $this->db_location = $db_location;
      $this->db_user = $db_user;
      $this->db_pass = $db_pass;
      $this->db_name = $db_name;
    }

    function connectToDatabase()
    {
      $log = fopen('./logs/log.txt', 'a');

      $this->db_con = mysqli_connect($this->db_location, $this->db_user, $this->db_pass, $this->db_name);
      mysqli_set_charset($this->db_con, 'utf8');

      if (!$this->db_con) {
        $text = date("Y-m-d H:m:s") . " fail to connect to database. check config.php\n";
        fwrite($log, $text);
        exit('Warning: check log file for more information');
      } else {
        $text = date("Y-m-d H:i:s") . " connected to database successfully\n";
        fwrite($log, $text);
      }
    }
  }
