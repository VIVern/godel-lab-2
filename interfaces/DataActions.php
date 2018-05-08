<?php
  interface DataActions
  {
    public function setData($data,$table);
    public function getData($table);
    public function removeData($table);
    public function updateData();
  }
