<?php

namespace Asker;

require_once 'Exception.php';

Class Asker
{
  public function __construct($protocol)
  {
    return new Adaptater($protocol);
  }
}