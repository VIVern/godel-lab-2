<?php
  class View
  {
    public function showData($template, $data=null, $dayFilter=7)
    {
      include_once $template;
    }
    public function showStaticPage($template)
    {
      include_once $template;
    }
  }
