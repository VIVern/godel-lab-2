<?php
  interface DataActions
  {
    public function setData($data,$table);
    public function getData();
    public function removeData($table);
    public function updateData();
  }
