<?php
  include_once  './interfaces/DataActions.php';
  include_once 'Mysql.php';
  include_once 'Film.php';

  class FilmData extends Mysql implements DataActions
  {
    public $films=[];

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
        echo $querry;
        echo "<hr>";
        $req = mysqli_query($this->db_con, $querry);
      }
    }

    public function getData()
    {
      //select from database
      $querry = "SELECT * FROM films";
      $req = mysqli_query($this->db_con, $querry);

      while ($result = mysqli_fetch_array($req)) {
        array_push($this->films, new Film ($result['title'], $result['titleOriginal'], $result['poster'], $result['overview'], $result['releaseDate'], $result['genres']));
      }
    }

    public function removeData($table)
    {
      $querry = "DELETE FROM " . $table;
      $req = mysqli_query($this->db_con, $querry);
    }

    public function updateData()
    {
      echo "";
    }
  }
