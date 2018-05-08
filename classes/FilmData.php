<?php
  include_once  './interfaces/DataActions.php';
  include_once 'Mysql.php';
  include_once 'Film.php';

  class FilmData extends Mysql implements DataActions
  {
    protected $films=[];

    public function setData()
    {
      $this->removeData();

      foreach ($this->films as $film) {
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

    public function getData($days=7)
    {
      $dayFilter = $days;

      //select from database
      $querry = "SELECT * FROM films";
      $req = mysqli_query($this->db_con, $querry);

      while ($result = mysqli_fetch_array($req)) {
        array_push($this->films, new Film ($result['title'], $result['titleOriginal'], $result['poster'], $result['overview'], $result['releaseDate'], $result['genres']));
      }
      include_once './view/films.phtml';
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

    // function getNewData()
    // {
    //   $options = [
    //     'http' => [
    //       'method' => "GET",
    //       'header' => 'Content-type: application/x-www-form-urlencoded'
    //     ]
    //   ];
    //
    //   $context = stream_context_create($options);
    //
    //   $query = json_decode(file_get_contents('https://api.themoviedb.org/3/movie/now_playing?api_key=e3c790bdb811cade513e875f4806841d&language=ru&page=1&region=Ru', false, $context), true);
    //   $countPages = $query['total_pages'];
    //   $result = $query['results'];
    //
    //   for ($i = 2; $i <= $countPages; $i++) {
    //     $query = json_decode(file_get_contents('https://api.themoviedb.org/3/movie/now_playing?api_key=e3c790bdb811cade513e875f4806841d&language=ru&page=' . $i . '&region=Ru', false, $context), true);
    //     $page = $query['results'];
    //     $result = array_merge($result, $page);
    //   }
    //
    //
    //   $query = json_decode(file_get_contents('https://api.themoviedb.org/3/genre/movie/list?api_key=e3c790bdb811cade513e875f4806841d&language=ru', false, $context), true);
    //   $genre = $query['genres'];
    //
    //   // clear uploads folder
    //   if (file_exists('./uploads/') === true) {
    //     foreach (glob('./uploads/*') as $file) {
    //       unlink($file);
    //     }
    //   }
    //
    //   for ($i = 0; $i < count($result); $i++) {
    //     array_push($this->films, new Film ($result[$i]['title'], $result[$i]['original_title'], $result[$i]['poster_path'], $result[$i]['overview'], $result[$i]['release_date'], $result[$i]['genre_ids']));
    //     $this->films[$i]->getGenres($genre);
    //     $this->films[$i]->getPoster($i);
    //   }
    //
    //   return $this->films;
    // }
  }
