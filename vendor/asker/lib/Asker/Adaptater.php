<?php

namespace Asker;

require_once 'Exception.php';

Class Adaptater
{

  Const SNMP  = 10;
  Const SSH   = 20;
  Const HTTP  = 30;

  private $_protocols = array(
    self::SNMP  => 'SNMP',
    self::SSH   => 'SSH',
    self::HTTP  => 'HTTP',
  );

  public function __construct($protocol)
  {
    switch ($protocol) {

      case self::SNMP:
        return new Adaptater\Snmp();
        break;

      case self::SSH:
        return new Adaptater\Ssh();
        break;

      case self::HTTP:
        return new Adaptater\Http();
        break;

      default:
        throw new Asker_Exception("ERROR: Adaptater not exist !");
    }
  }

  public function getProtocols($protocol=null)
  {
    if (!is_null($protocol) AND array_key_exists($protocol)) {
      return $this->_protocols[$protocol];
    }
    return $this->_protocols;
  }
}