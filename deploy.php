<?php
  require_once './config/config.php';
  include_once './classes/Logger.php';
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

  $db_con = mysqli_connect($db_location, $db_user, $db_pass, $db_name);
  $response = mysqli_query($db_con, $query);

  $query = "CREATE TABLE shows (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name TINYTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
    originalName TINYTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
    poster TINYTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
    overview TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
    releaseDate DATE NOT NULL ,
    genres TINYTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
  )";

  $db_con = mysqli_connect($db_location, $db_user, $db_pass, $db_name);
  $response = mysqli_query($db_con, $query);

  if ($db_con === false) {
    Logger::writeMessage("Failed to connect to database. Check Mysql server status and config.php file");
    exit("Warning: check log file for more information\n");
  }
  elseif ($response === false) {
    Logger::writeMessage("Failed to create table. Check Mysql querry in deploy.php file");
    exit("Warning: check log file for more information\n");
  } else {
    Logger::writeMessage("Table was created successfully");
    echo "operation complite\n";
  }
