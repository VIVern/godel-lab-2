<?php
  class Logger
  {
    private static function open($path)
    {
      $logFile = fopen($path, "a");
      return $logFile;
    }

    public static function writeMessage($text, $path = './logs/log.txt')
    {
      $file = self::open($path);
      $message = date("Y-m-d H:i:s") . " : " . $text . "\n";
      fwrite($file, $message);
    }
  }
