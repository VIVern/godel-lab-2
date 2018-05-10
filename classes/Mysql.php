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
      $this->db_con = mysqli_connect($this->db_location, $this->db_user, $this->db_pass, $this->db_name);
      mysqli_set_charset($this->db_con, 'utf8');

      if ($this->db_con === false) {
        Logger::writeMessage("Failed to connect to database. Check Mysql server status and config.php file");
        exit("Warning: check log file for more information\n");
      } else {
        Logger::writeMessage("Connected to database successfully");
      }
      return $this->db_con;
    }

    public function setData($data, $table)
    {
      $this->removeData($table);

      foreach ($data as $film) {
        $vars = get_object_vars($film);
        $values="( NULL";
        foreach ($vars as $var => $value)
        {
          if ($value !== NULL) {
            $values .= " ,'" . $value ."'";
          }
        }
        $querry = "INSERT INTO " . $table . " VALUES " . $values .  ")";
        $req = mysqli_query($this->db_con, $querry);
      }
    }

    public function getData($table)
    {
      //select from database
      $querry = "SELECT * FROM " . $table;
      $req = mysqli_query($this->db_con, $querry);

      $responseData=[];
      while ($result = mysqli_fetch_array($req)) {
        array_push($responseData, $result);
      }
      return $responseData;
    }

    public function removeData($table)
    {
      $querry = "DELETE FROM " . $table;
      $req = mysqli_query($this->db_con, $querry);
    }

    public function updateData()
    {
      throw new Exception("not implemented");
    }
  }
