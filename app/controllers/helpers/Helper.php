<?php

Namespace Controller;

Class Helper
{
  public function __construct()
  {
  }

  public function __call($name, $args)
  {
    $name = "Controller\\Helper\\".$name;
    if (class_exists($name)) {
      return new $name($args);
    }
  }
}