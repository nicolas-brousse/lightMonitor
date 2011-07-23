<?php

namespace Asker\Adaptater;

require_once 'Interface.php';

Abstract Class Base implements Asker_Interface
{
  protected $_host;
  protected $_port;

  final public function __construct()
  {
    $this->init();
  }

  final public function setHost($host, $port)
  {
    $this->_host = $host;
    $this->_port = $port;
    return $this;
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