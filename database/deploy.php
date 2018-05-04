<?php
  require_once '../config/config.php';

  $query = "CREATE TABLE films (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    title TINYTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
    titleOriginal TINYTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
    poster TINYTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
    overview TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
    releaseDate DATE NOT NULL ,
    genres TINYTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
  )";

  mysqli_query($db_con, $query);
