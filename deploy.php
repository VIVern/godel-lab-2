<?php
  require_once './config/config.php';
  $log = fopen('logs/log.txt', 'a');

  $query = "CREATE TABLE films (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    title TINYTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
    titleOriginal TINYTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
    poster TINYTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
    overview TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
    releaseDate DATE NOT NULL ,
    genres TINYTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
  )";

  $response = mysqli_query($db_con, $query);

  if ($response === false) {
    $text = date("Y-m-d H:m:s") . " failed to create table. Check your query and connection to database.\n";
    fwrite($log, $text);
    exit('Warning: check log file for more information');
  } else {
    $text = date("Y-m-d H:m:s") . " table was created successfully\n";
    fwrite($log, $text);
    echo "operation complite";
  }
