<?php
  //подключение к базе
  $db_location = 'localhost';
  $db_user = 'root';
  $db_pass = '123';
  $db_name = 'lab2';

  $db_con = mysqli_connect($db_location, $db_user, $db_pass, $db_name);

  if(!$db_con){
    exit('error');
  }
