<?php

namespace Asker;

Class Adaptater
{

  Const SNMP  = 0;
  Const SSH   = 10;
  Const HTTP  = 20;

  public function __construct($type)
  {
    switch ($type) {
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
        throw new Asker_Exception("ERROR: Adaptater '$type' not exist !");
    }
  }
}