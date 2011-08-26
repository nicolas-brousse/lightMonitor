<?php

Namespace Controller\Helper;

Abstract Class Base
{
  final public function __construct($args=null)
  {
    if (method_exists($this, 'init')) {
      $this->init($args);
    }
  }
}