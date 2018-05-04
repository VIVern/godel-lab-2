<?php
  //подключение к базе
  $db_location = 'localhost';
  $db_user = 'root';
  $db_pass = '123';
  $db_name = 'deploy_test';

  $db_con = mysqli_connect($db_location, $db_user, $db_pass, $db_name);
  mysqli_set_charset($db_con, 'utf8');

  if(!$db_con){
    exit('error');
  }
