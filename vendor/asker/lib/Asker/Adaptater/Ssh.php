<?php
/**
 *
 * Asker Extension
 * @adaptater SSH
 *
 * @package Asker Extension
 * @version 1
 * @author Nicolas BROUSSE <pro@nicolas-brousse.fr>
 */

namespace Asker\Adaptater;

use Asker\Asker_Adaptater_Exception;

Class Ssh extends Base
{
  private $_connection = false;

  public function init()
  {
    if (!function_exists('ssh2_connect')) {
      /**
       * @see Asker\Adaptater\Exception
       */
      require_once 'Exception.php';
      throw new Asker_Adaptater_Exception("ERROR: To use SSH protocol, install PHP extention for SSH (php5-ssh2) !");
    }

    /**
     * Verif configurations
     */
    $config = $this->_config;
    if (!isset($config["host"]) OR empty($config["host"])) {
      /**
       * @see Asker\Adaptater\Exception
       */
      require_once 'Exception.php';
      throw new Asker_Adaptater_Exception("ERROR: Precise host !");
    }
    if (!isset($config["login"]) OR empty($config["login"])) {
      /**
       * @see Asker\Adaptater\Exception
       */
      require_once 'Exception.php';
      throw new Asker_Adaptater_Exception("ERROR: Precise login !");
    }
    if (!isset($config["pass"])) {
      /**
       * @see Asker\Adaptater\Exception
       */
      require_once 'Exception.php';
      throw new Asker_Adaptater_Exception("ERROR: To use SSH protocol, install PHP extention for SSH (php5-ssh2) !");
    }

    /**
     * Generate SSH connection
     */
    $this->_connection = @ssh2_connect($config["host"], !empty($config["port"]) ? $config["port"] : 22);
    if (!$this->_connection) {
      /**
       * @see Asker\Adaptater\Exception
       */
      require_once 'Exception.php';
      throw new Asker_Adaptater_Exception("ERROR: SSH Connection to '{$config["host"]}:{$config["port"]}' failed !");
    }
    if (!@ssh2_auth_password($this->_connection, $config["login"], $config["pass"])) {
      /**
       * @see Asker\Adaptater\Exception
       */
      require_once 'Exception.php';
      throw new Asker_Adaptater_Exception("ERROR: Authentication failed for {$config["login"]} using password !");
    }
    // TODO set Host and Log
    // TODO Can Log with public key
  }

  public function __destruct()
  {
    $this->_logout();
  }

  private function _logout() {
    if ($this->_exec('logout') != false) {
      unset($this);
      return true;
    }
    else {
      return false;
    }
  }

  private function _exec($cmd)
  {
    $stdout_stream=@ssh2_exec($this->_connection, $cmd);
    if(!$stdout_stream) {
      return false;
    }

    $stderr_stream=@ssh2_fetch_stream($stdout_stream, SSH2_STREAM_STDERR);
    if(!$stderr_stream) {
      return false;
    }

    if(!@stream_set_blocking($stdout_stream, true)) {
      return false;
    }
    if(!@stream_set_blocking($stderr_stream, true)) {
      return;
    }

    return array($stdout_stream, $stderr_stream);
  }

  private function _execResult($stream)
  {
    return utf8_encode(stream_get_contents($stream));
  }

  public function getUptime()
  {
    $return = $this->_exec('uptime');
    $result = $this->_execResult($return[0]);

    preg_match("#averages?: ([0-9\.]+),[\s]+([0-9\.]+),[\s]+([0-9\.]+)#",$result,$avgs); 

    return array($avgs[1], $avgs[2], $avgs[3]);
  }

  public function getMemory()
  {
    $return = $this->_exec('cat /proc/meminfo');
    $result = $this->_execResult($return[0]);

    preg_match("#^MemTotal:\s*(\d+) kB\s*MemFree:\s*(\d+) kB#",$result,$mem); 

    return array($mem[1], $mem[2]);
  }

  public function getTraffic()
  {
    $return = $this->_exec('cat /proc/net/dev');
    $result = $this->_execResult($return[0]);

    preg_match("#eth0:\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)#",$result,$traffic);

    return array($traffic[1], $traffic[9]);
  }

  public function getCpu()
  {
    $return = $this->_exec('cat /proc/stat');
    $result = $this->_execResult($return[0]);

    preg_match("#^cpu\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)#",$result,$cpu); 

    return array($cpu[1], $cpu[2], $cpu[3], $cpu[4], $cpu[5], $cpu[6], $cpu[7]);
  }
}