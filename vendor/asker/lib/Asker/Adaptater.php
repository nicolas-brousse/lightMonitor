<?php
/**
 *
 * Asker adapter
 * Caller Class for Adapters
 *
 * @package Asker Extension
 * @version 1
 * @author Nicolas BROUSSE <pro@nicolas-brousse.fr>
 **/

namespace Asker;

Class Adapter
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
        return new Adapter\Snmp($config);
        break;

      case self::SSH:
        return new Adapter\Ssh($config);
        break;

      case self::HTTP:
        return new Adapter\Http($config);
        break;

      default:
        /**
         * @see Asker\Adapter\Exception
         */
        require_once 'Adapter/Exception.php';
        throw new Asker_Adapter_Exception("ERROR: Adapter not exist !");
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