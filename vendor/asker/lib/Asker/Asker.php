<?php

namespace Asker;

require_once 'Exception.php';

Class Asker
{
  private $_adaptater;

  public function __construct($protocol)
  {
    $this->_adaptater = new Adaptater($protocol);
    return $this->_adaptater;
  }
}