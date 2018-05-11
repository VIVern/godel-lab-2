<?php
  include_once 'Film.php';

  class FilmData
  {
    public $films = [];

    public function createFilmUnits($filmsArray)
    {
      $films = [];
      foreach ($filmsArray as $film) {
        array_push($films, new Film ($film['filmId'] ,$film['title'], $film['titleOriginal'], $film['poster'], $film['overview'], $film['releaseDate'], $film['genres'], $flim['runtime']));
      }
      return $films;
    }

    public function parseFilms($films, $genres)
    {
      $this->clearUploads();

      for ($i = 0; $i < count($films); $i++) {
        array_push($this->films, new Film (
          $films[$i]['id'],
          $films[$i]['title'],
          $films[$i]['original_title'],
          $films[$i]['poster_path'],
          $films[$i]['overview'],
          $films[$i]['release_date'],
          $films[$i]['genre_ids']
        ));
        $this->films[$i]->setGenres($genres);
        $this->films[$i]->getPoster($i);
        $this->films[$i]->getRuntime();
      }
    }

    private function clearUploads()
    {
      if (file_exists('./uploads/films/') === true) {
        foreach (glob('./uploads/films/*') as $file) {
          unlink($file);
        }
        Logger::writeMessage("Uploads/films folder was cleared");
      }
    }
  }
