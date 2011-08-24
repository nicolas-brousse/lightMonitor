<?php

Namespace Model;

Class Server extends Base
{
  public static function getInstance()
  {
    if (!isset(self::$instance)) {
      $className = __CLASS__;
      self::$instance = new $className;
    }
    return self::$instance;
  }

  public function save()
  {
    
  }
}