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

Class Adaptater
{

  Const SNMP  = 10;
  Const SSH   = 20;
  Const HTTP  = 30;

  static private $_adapters = array(
    self::SNMP  => 'SNMP',
    self::SSH   => 'SSH',
    self::HTTP  => 'HTTP',
  );

  final public static function factory($adapter, $config=array())
  {
    switch ($adapter) {

      case self::SNMP:
        return new Adaptater\Snmp($config);
        break;

      case self::SSH:
        return new Adaptater\Ssh($config);
        break;

      case self::HTTP:
        return new Adaptater\Http($config);
        break;

      default:
        /**
         * @see Asker\Adaptater\Exception
         */
        require_once 'Adaptater/Exception.php';
        throw new Asker_Adaptater_Exception("ERROR: Adaptater not exist !");
    }
  }

  final public static function getProtocols($adapter=null)
  {
    if (!is_null($adapter) AND array_key_exists($adapter, self::$_adapters)) {
      return self::$_adapters[$adapter];
    }
    return self::$_adapters;
  }
}