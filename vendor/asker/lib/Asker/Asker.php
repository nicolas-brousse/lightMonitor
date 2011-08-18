<?php
/**
 *
 * Asker
 * Entering class
 *
 * @package Asker Extension
 * @version 1
 * @author Nicolas BROUSSE <pro@nicolas-brousse.fr>
 **/

namespace Asker;

Class Asker
{
  Const ADAPTER_SNMP  = 10;
  Const ADAPTER_SSH   = 20;
  Const ADAPTER_HTTP  = 30;
  Const ADAPTER_SSH_PUBKEY   = 40;

  static private $_adapters = array(
    //self::ADAPTER_SNMP  => 'SNMP',
    self::ADAPTER_SSH   => 'SSH - Password',
    //self::ADAPTER_HTTP  => 'HTTP',
    self::ADAPTER_SSH_PUBKEY => 'SSH - Pubkey',
  );

  private $_adapter;

  final public static function getProtocols($adapter=null)
  {
    if (!is_null($adapter) AND array_key_exists($adapter, self::$_adapters)) {
      return self::$_adapters[$adapter];
    }
    return self::$_adapters;
  }

  public static function factory($adapter, $config=array())
  {
    /*
     * Verify that adapter parameters are in an array.
     */
    if (!is_array($config)) {
        /**
         * @see Asker\Exception
         */
        require_once 'Exception.php';
        throw new Asker_Exception('Adapter parameters must be in an array');
    }

    /*
     * Verify that an adapter name has been specified.
     */
    if (empty($adapter)) {
        /**
         * @see Asker\Exception
         */
        require_once 'Exception.php';
        throw new Asker_Exception('Adapter name must be specified');
    }

    /*
     * Create an instance of the adapter ask.
     * Pass the config to the adapter.
     */
    switch ($adapter) {

        case self::ADAPTER_SNMP:
          $askerAdapter = new Adapter\Snmp($config);
          break;

        case self::ADAPTER_SSH:
          $askerAdapter = new Adapter\Ssh($config);
          break;

        case self::ADAPTER_SSH_PUBKEY:
          $askerAdapter = new Adapter\SshPubkey($config);
          break;

        case self::ADAPTER_HTTP:
          $askerAdapter = new Adapter\Http($config);
          break;

        default:
          /**
           * @see Asker\Adapter\Exception
           */
          require_once 'Adapter/Exception.php';
          throw new Asker_Adapter_Exception("ERROR: Adapter not exist !");
      }

    /*
     * Verify that the object created is a descendent of the abstract adapter type.
     */
    if (!$askerAdapter instanceof Adapter\Base) {
        /**
         * @see Asker_Exception
         */
        require_once 'Exception.php';
        throw new Asker_Exception("Adapter class '$adapterName' does not extend Asker\Adapter\Base");
    }

    return $askerAdapter;
  }
}
