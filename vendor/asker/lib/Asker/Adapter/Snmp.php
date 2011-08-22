<?php
/**
 *
 * Asker Extension
 * @adapter SNMP
 *
 * @package Asker Extension
 * @version 1
 * @author Nicolas BROUSSE <pro@nicolas-brousse.fr>
 */

namespace Asker\Adapter;

use Asker\Asker_Adapter_Exception;

Class Snmp extends Base
{
  protected function _verifDependencies()
  {
    if (!class_exists('SNMP')) {
      /**
       * @see Asker\Adapter\Exception
       */
      require_once 'Exception.php';
      throw new Asker_Adapter_Exception("ERROR: To use HTTP protocol, install php5-snmp PHP extention !");
    }
  }

  public function init()
  {
    /*$host = "shell.example.com";
    $port = 22;
    $this->connection = ssh2_connect($host, $port);
    if (!$this->connection) {
      throw new Asker_Adapter_Exception("ERROR: SSH Connection to '$host:$port' failed !");
    }
    if (ssh2_auth_password($this->connection, 'username', 'secret')) {
      throw new Asker_Adapter_Exception('ERROR: SSH Connection failed, invalid username or password !');
    }*/

    # http://www.debianadmin.com/linux-snmp-oids-for-cpumemory-and-disk-statistics.html
    # http://www.net-snmp.org/wiki/index.php/TUT:snmpwalk
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