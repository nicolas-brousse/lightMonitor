<?php

Namespace Model;

use App;

Abstract Class Base
{
  private static $instance;
  protected $db;

  final public function __construct()
  {
    $app = App::getInstance();
    $this->db = $app['db'];
  }

  public static function getInstance()
  {
    if (!isset(self::$instance)) {
      $className = get_called_class();
      self::$instance = new $className;
    }
    return self::$instance;
  }
}