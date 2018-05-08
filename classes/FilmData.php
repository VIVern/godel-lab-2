<?php
  include_once  './interfaces/DataActions.php';
  include_once 'Mysql.php';
  include_once 'Film.php';

  class FilmData extends Mysql implements DataActions
  {
    public $films=[];

    public function setData($data)
    {
      $this->removeData();

      foreach ($data as $film) {
        $title = $film->title;
        $titleOriginal = $film->titleOriginal;
        $poster = $film->poster;
        $overview = $film->overview;
        $realeseDate = $film->releaseDate;
        $genre = $film->genres;

        $querry = "INSERT INTO films VALUES( NULL, '$title', '$titleOriginal', '$poster', '$overview', '$realeseDate', '$genre')";
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

    public function removeData()
    {
      $querry = "DELETE FROM films";
      $req = mysqli_query($this->db_con, $querry);
    }

    public function updateData()
    {
      echo "";
    }
  }
