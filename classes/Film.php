<?php
  class Film
  {
    public $title;
    public $titleOriginal;
    public $poster;
    public $overview;
    public $releaseDate;
    //public $runtime;                                                                  //there is no such option in json response;
    public $genres;

    public function __construct($param1, $param2, $param3, $param4, $param5, $param6)
    {
      $this->title = $param1;
      $this->titleOriginal = $param2;
      $this->poster = $param3;
      $this->overview = $param4;
      $this->releaseDate = $param5;
      $this->genres = $param6;
    }

    public function setGenres($genreArray)
    {
      $genresList = [];
      foreach ($this->genres as $id) {
        foreach ($genreArray as $val) {
          if($val['id'] === $id) {
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
        $path = './uploads/film_'. $name .'.jpg';
        file_put_contents($path, file_get_contents($url));
        $this->poster = $path;
    }
  }
}
