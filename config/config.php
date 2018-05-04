<?php
  $log = fopen('./logs/log.txt', 'a');

  $API_token = '';

  //подключение к базе
  $db_location = 'localhost';
  $db_user = '';
  $db_pass = '';
  $db_name = '';

  $db_con = mysqli_connect($db_location, $db_user, $db_pass, $db_name);
  mysqli_set_charset($db_con, 'utf8');

  if (!$db_con) {
    $text = date("Y-m-d H:m:s") . " fail to connect to database. check config.php\n";
    fwrite($log, $text);
    exit('Warning: check log file for more information');
  } else {
    $text = date("Y-m-d H:i:s") . " connected to database successfully\n";
    fwrite($log, $text);
  }
