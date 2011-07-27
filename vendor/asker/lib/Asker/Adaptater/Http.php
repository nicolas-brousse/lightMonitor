<?php
/**
 *
 * Asker Extension
 * @adaptater HTTP
 *
 * @package Asker Extension
 * @version 1
 * @author Nicolas BROUSSE <pro@nicolas-brousse.fr>
 */

namespace Asker\Adaptater;

use Asker\Asker_Adaptater_Exception;

Class Http extends Base
{
  public function init()
  {
    if (!function_exists('curl_init')) {
      throw new Asker_Adaptater_Exception("ERROR: To use HTTP protocol, install php5-curl PHP extention !");
    }

    /*$host = "shell.example.com";
    $port = 22;
    $this->connection = ssh2_connect($host, $port);
    if (!$this->connection) {
      throw new Asker_Adaptater_Exception("ERROR: SSH Connection to '$host:$port' failed !");
    }
    if (ssh2_auth_password($this->connection, 'username', 'secret')) {
      throw new Asker_Adaptater_Exception('ERROR: SSH Connection failed, invalid username or password !');
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