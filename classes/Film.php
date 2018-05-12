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

    public function __construct($param1, $param2, $param3, $param4, $param5, $param6, $param7="", $param8 = 0)
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

    // public function getPoster($name)
    // {
    //   if (isset($this->poster) === true) {
    //     $url = 'https://image.tmdb.org/t/p/w200/' . $this->poster;
    //     $path = './uploads/films/film_' . $name . '.jpg';
    //     $poster = file_get_contents($url);
    //
    //     if ($path === false) {
    //       Logger::writeMessage("Failed to download poster from tmdb. Check tmdb server status and request url");
    //     } else {
    //       Logger::writeMessage("Poster was received successfully");
    //     }
    //
    //     file_put_contents($path, $poster);
    //     $this->poster = $path;
    //   }
    // }
  }
