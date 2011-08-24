<?php

Namespace Model;

Abstract Class Base
{
  private static $instance;

  final public function __construct()
  {
    
  }

  public static function getInstance()
  {
    if (!isset(self::$instance)) {
      $className = __CLASS__;
      self::$instance = new $className;
    }
    return self::$instance;
  }
}