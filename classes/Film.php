<?php
  class Film
  {
    public $id;
    public $title;
    public $titleOriginal;
    public $poster;
    public $overview;
    public $releaseDate;
    public $genres;
    public $runtime;

    public function __construct($param1, $param2, $param3, $param4, $param5, $param6, $param7="", $param8 = NULL)
    {
      $this->id = $param1;
      $this->title = $param2;
      $this->titleOriginal = $param3;
      $this->poster = $param4;
      $this->overview = $param5;
      $this->releaseDate = $param6;
      $this->genres = $param7;
      $this->runtime = $param8;
    }
  }
