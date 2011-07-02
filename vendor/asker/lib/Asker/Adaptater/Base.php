<?php

namespace Asker\Adaptater;

require_once 'Interface.php';

Abstract Class Base implements Asker_Interface
{
  final public function __construct()
  {
    $this->init();
  }

  public function getUptime()
  {

  }

  public function getMemory()
  {

  }

  public function getTraffic()
  {

  }

  public function getCpu()
  {

  }
}