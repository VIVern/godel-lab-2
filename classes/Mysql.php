<?php
  include_once './abstract_classes/Database.php';

  class Mysql extends Database
  {
    private $db_location = '';
    private $db_user = '';
    private $db_pass = '';
    private $db_name = '';
    protected $db_con;

    public function __construct($db_location, $db_user, $db_pass, $db_name)
    {
      $this->db_location = $db_location;
      $this->db_user = $db_user;
      $this->db_pass = $db_pass;
      $this->db_name = $db_name;
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
      $this->connect();

      $this->removeData($table);

      foreach ($data as $film) {
        $vars = get_object_vars($film);
        $values = "( NULL";
        foreach ($vars as $var => $value) {
          if ($value !== NULL) {
            $values .= ", '" . $value . "'";
          }
        }
        $querry = "INSERT INTO " . $table . " VALUES " . $values . ")";
        $req = mysqli_query($this->db_con, $querry);

        if ($req === false) {
          Logger::writeMessage("Failed insert data. Check querry and connection to database.");
          Logger::writeMessage("Query: " . $querry);
        } else {
          Logger::writeMessage("Data was inserted successfully");
        }
      }
    }

    public function getData($table)
    {
      $this->connect();
      //select from database
      $querry = "SELECT * FROM " . $table;
      $req = mysqli_query($this->db_con, $querry);

      if ($req === false) {
        Logger::writeMessage("Failed to select data from tabel. Check connection to database and table name");
        exit("Warning: check log file for more information\n");
      } else {
        Logger::writeMessage("Data was selected successfully");
      }

      $responseData = [];
      while ($result = mysqli_fetch_array($req)) {
        array_push($responseData, $result);
      }
      return $responseData;
    }

    public function removeData($table)
    {
      $this->connect();
      $querry = "DELETE FROM " . $table;
      $req = mysqli_query($this->db_con, $querry);

      if ($req === false) {
        Logger::writeMessage("Failed to clear tabel. Check connection to database and table name");
        exit("Warning: check log file for more information\n");
      } else {
        Logger::writeMessage("Table was cleared successfully");
      }
    }

    public function updateData($table)
    {
      throw new Exception("not implemented");
    }
  }
