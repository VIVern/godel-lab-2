<?php
  abstract class Database
  {
    abstract protected function connect();
    abstract public function setData($data,$table);
    abstract public function getData($table);
    abstract public function removeData($table);
    abstract public function updateData();
  }
