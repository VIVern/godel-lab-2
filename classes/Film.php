<?php
  class Film
  {
    public $title;
    public $titleOriginal;
    public $poster;
    public $overview;
    public $releaseDate;
    //public $runtime;                                                                  //there is no such option in json response;
    public $genresId;
    public $genres;

    public function __construct($param1, $param2, $param3, $param4, $param5, $param6)
    {
      $this->title = $param1;
      $this->titleOriginal = $param2;
      $this->poster = $param3;
      $this->overview = $param4;
      $this->releaseDate = $param5;
      $this->genresId = $param6;
    }

    public function getGenres($genreArray)
    {
      $genresList = [];
      foreach ($this->genresId as $id) {
        foreach ($genreArray as $val) {
          if($val['id'] === $id) {
            array_push($genresList, $val['name']);
          }
        }
      }
      $this->genres = implode(',', $genresList);
    }
  }
