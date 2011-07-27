<?php
/**
 *
 * Asker Extension
 * @adaptater SNMP
 *
 * @package Asker Extension
 * @version 1
 * @author Nicolas BROUSSE <pro@nicolas-brousse.fr>
 */

namespace Asker\Adaptater;

use Asker\Asker_Adaptater_Exception;

Class Snmp extends Base
{
  public function init()
  {
    if (!class_exists('SNMP')) {
      throw new Asker_Adaptater_Exception("ERROR: To use SNMP protocol, install php5-snmp PHP extention !");
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