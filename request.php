<?php
  include_once 'config/config.php';

  $options = [
    'http' => [
      'method' => "GET",
      'header' => 'Content-type: application/x-www-form-urlencoded'
    ]
  ];

  $context = stream_context_create($options);

  $query = json_decode(file_get_contents('https://api.themoviedb.org/3/movie/now_playing?api_key=' . $API_token . '&language=ru&page=1&region=Ru', false, $context), true);
  $countPages = $query['total_pages'];
  $result = $query['results'];

  for ($i = 2; $i <= $countPages; $i++) {
    $query = json_decode(file_get_contents('https://api.themoviedb.org/3/movie/now_playing?api_key=' . $API_token . '&language=ru&page=' . $i . '&region=Ru', false, $context), true);
    $page = $query['results'];
    $result = array_merge($result, $page);
  }


  $query = json_decode(file_get_contents('https://api.themoviedb.org/3/genre/movie/list?api_key=' . $API_token . '&language=ru', false, $context), true);
  $genre = $query['genres'];
