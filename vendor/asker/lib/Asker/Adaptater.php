<?php
/**
 *
 * Asker Adaptater
 * Caller Class for adaptaters
 *
 * @package Asker Extension
 * @version 1
 * @author Nicolas BROUSSE <pro@nicolas-brousse.fr>
 **/

namespace Asker;

require_once 'Exception.php';
require_once 'Adaptater/Exception.php';

Class Adaptater
{

  Const SNMP  = 10;
  Const SSH   = 20;
  Const HTTP  = 30;

  static private $_protocols = array(
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
        throw new Asker_Adaptater_Exception("ERROR: Adaptater not exist !");
    }
  }

  final static public function getProtocols($protocol=null)
  {
    if (!is_null($protocol) AND array_key_exists($protocol, self::$_protocols)) {
      return self::$_protocols[$protocol];
    }
    return self::$_protocols;
  }
}