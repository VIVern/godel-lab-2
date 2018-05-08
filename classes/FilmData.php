<?php
  include_once 'Film.php';

  class FilmData
  {
    public function createFilmUnits($filmsArray)
    {
      $films=[];
      foreach ($filmsArray as $film) {
        array_push($films, new Film ($film['title'], $film['titleOriginal'], $film['poster'], $film['overview'], $film['releaseDate'], $film['genres']));
      }
      return $films;
    }
  }
