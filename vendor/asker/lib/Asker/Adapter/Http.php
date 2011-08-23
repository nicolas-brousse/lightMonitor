<?php
/**
 *
 * Asker Extension
 * @adapter HTTP
 *
 * @package Asker Extension
 * @version 1
 * @author Nicolas BROUSSE <pro@nicolas-brousse.fr>
 */

namespace Asker\Adapter;

use Asker\Asker_Adapter_Exception;

Class Http extends Base
{
  protected function _verifDependencies()
  {
    if (!function_exists('curl_init')) {
      /**
       * @see Asker\Adapter\Exception
       */
      require_once 'Exception.php';
      throw new Asker_Adapter_Exception("ERROR: To use HTTP protocol, install php5-curl PHP extention !");
    }
  }

  public function init()
  {
    /*$host = "shell.example.com";
    $port = 22;
    $this->connection = ssh2_connect($host, $port);
    if (!$this->connection) {
      throw new Asker__Exception("ERROR: SSH Connection to '$host:$port' failed !");
    }
    if (ssh2_auth_password($this->connection, 'username', 'secret')) {
      throw new Asker__Exception('ERROR: SSH Connection failed, invalid username or password !');
    }*/
    
    #
    # Componant Symphony Client ???
    #
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