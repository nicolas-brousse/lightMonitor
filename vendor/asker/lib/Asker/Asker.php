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
  private $_adaptater;

  public static function getProtocols($protocol=null)
  {
    return Adaptater::getProtocols($protocol);
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
     * Create an instance of the adapter class.
     * Pass the config to the adapter.
     */
    $askerAdapter = Adaptater::factory($adapter, $config);

    /*
     * Verify that the object created is a descendent of the abstract adapter type.
     */
    if (! $askerAdapter instanceof Adaptater\Base) {
        /**
         * @see Asker_Exception
         */
        require_once 'Exception.php';
        throw new Asker_Exception("Adapter class '$adapterName' does not extend Asker\Adaptater\Base");
    }

    return $askerAdapter;
  }
}
