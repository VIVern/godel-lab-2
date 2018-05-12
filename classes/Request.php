<?php
  include_once 'Film.php';

  class Request
  {
    public $response;

    public function getData($url)
    {
      $options = [
        'http' => [
          'method' => "GET",
          'header' => 'Content-type: application/x-www-form-urlencoded'
        ]
      ];

      $context = stream_context_create($options);
      $query = json_decode(file_get_contents($url, false, $context), true);
      $this->response = $query;
    }
  }
