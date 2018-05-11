<?php
  class Film
  {
    public $id;
    public $title;
    public $titleOriginal;
    public $poster;
    public $overview;
    public $releaseDate;
    //public $runtime;                                                                  //there is no such option in json response;
    public $genres;

    public function __construct($param1, $param2, $param3, $param4, $param5, $param6,$param7)
    {
      $this->id = $param1;
      $this->title = $param2;
      $this->titleOriginal = $param3;
      $this->poster = $param4;
      $this->overview = $param5;
      $this->releaseDate = $param6;
      $this->genres = $param7;
    }

    public function setGenres($genreArray)
    {
      $genresList = [];
      foreach ($this->genres as $id) {
        foreach ($genreArray as $val) {
          if ($val['id'] === $id) {
            array_push($genresList, $val['name']);
          }
        }
      }
      $this->genres = implode(',', $genresList);
    }

    public function getPoster($name)
    {
      if (isset($this->poster) === true) {
        $url = 'https://image.tmdb.org/t/p/w200/' . $this->poster;
        $path = './uploads/films/film_' . $name . '.jpg';
        $poster = file_get_contents($url);

        if ($path === false) {
          Logger::writeMessage("Failed to download poster from tmdb. Check tmdb server status and request url");
        } else {
          Logger::writeMessage("Poster was received successfully");
        }

        file_put_contents($path, $poster);
        $this->poster = $path;

    }
  }
}
