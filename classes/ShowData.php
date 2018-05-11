<?php
  include_once 'Show.php';

  class ShowData
  {
    public $shows = [];

    public function createShowUnits($showsArray)
    {
      $shows = [];
      foreach ($showsArray as $show) {
        array_push($shows, new Show ($show['name'], $show['originalName'], $show['poster'], $show['overview'], $show['releaseDate'], $show['genres']));
      }
      return $shows;
    }

    public function parseShows($shows, $genres)
    {
      $this->clearUploads();

      for ($i = 0; $i < count($shows); $i++) {
        array_push($this->shows, new Show ($shows[$i]['name'], $shows[$i]['original_name'], $shows[$i]['poster_path'], $shows[$i]['overview'], $shows[$i]['first_air_date'], $shows[$i]['genre_ids']));
        $this->shows[$i]->setGenres($genres);
        $this->shows[$i]->getPoster($i);
      }
    }

    private function clearUploads()
    {
      if (file_exists('./uploads/shows/') === true) {
        foreach (glob('./uploads/shows/*') as $file) {
          unlink($file);
        }
        Logger::writeMessage("Uploads/shows folder was cleared");
      }
    }
  }
