<?php

namespace Asker;

require_once 'Exception.php';

Class Asker
{
  private $_adaptater;

  public function __construct($protocol)
  {
    $this->_adaptater = new Adaptater($protocol);
    if ($this->_adaptater)
      return $this->_adaptater;
    return false;
  }
}