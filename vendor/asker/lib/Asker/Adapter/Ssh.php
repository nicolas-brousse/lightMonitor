<?php
/**
 *
 * Asker Extension
 * @adapter SSH
 *
 * @package Asker Extension
 * @version 1
 * @author Nicolas BROUSSE <pro@nicolas-brousse.fr>
 */

namespace Asker\Adapter;

use Asker\Asker_Adapter_Exception;

Class Ssh extends Base
{
  protected $_paramsStructure = array(
    'port' => array('type' => 'integer'),
    'login' => array('type' => 'text'),
    'pass' => array('type' => 'password'),
  );

  private $_connection = false;

  protected function _verifDependencies()
  {
    if (!function_exists('ssh2_connect') || !function_exists('ssh2_auth_password')) {
      /**
       * @see Asker\Adapter\Exception
       */
      require_once 'Exception.php';
      throw new Asker_Adapter_Exception("ERROR: To use SSH protocol, install PHP extention for SSH (php5-ssh2) !");
    }
  }

  public function init()
  {
    /**
     * Verif configurations
     */
    $config = $this->_config;
    if (!isset($config["host"]) OR empty($config["host"])) {
      /**
       * @see Asker\Adapter\Exception
       */
      require_once 'Exception.php';
      throw new Asker_Adapter_Exception("ERROR: Precise host !");
    }
    if (!isset($config["login"]) OR empty($config["login"])) {
      /**
       * @see Asker\Adapter\Exception
       */
      require_once 'Exception.php';
      throw new Asker_Adapter_Exception("ERROR: Precise login !");
    }
    if (!isset($config["pass"])) {
      /**
       * @see Asker\Adapter\Exception
       */
      require_once 'Exception.php';
<<<<<<< HEAD
      throw new Asker_Adapter_Exception("ERROR: To use SSH protocol, install PHP extention for SSH (php5-ssh2) !");
=======
      throw new Asker_Adapter_Exception("ERROR: Precise password !");
>>>>>>> dev
    }

    /**
     * Generate SSH connection
     */
    $this->_connection = @ssh2_connect($config["host"], !empty($config["port"]) ? $config["port"] : 22);
    if (!$this->_connection) {
      /**
       * @see Asker\Adapter\Exception
       */
      require_once 'Exception.php';
      throw new Asker_Adapter_Exception("ERROR: SSH Connection to '{$config["host"]}:{$config["port"]}' failed !");
    }

    if (!@ssh2_auth_password($this->_connection, $config["login"], $config["pass"])) {
      /**
       * @see Asker\Adapter\Exception
       */
      require_once 'Exception.php';
      throw new Asker_Adapter_Exception("ERROR: Authentication failed for {$config["login"]} using password !");
    }
<<<<<<< HEAD
    // TODO set Host and Log
    // TODO Can Log with public key
=======
>>>>>>> dev
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
<<<<<<< HEAD
    $stdout_stream=@ssh2_exec($this->_connection, $cmd);
=======
    $stdout_stream = @ssh2_exec($this->_connection, $cmd);
>>>>>>> dev
    if(!$stdout_stream) {
      return false;
    }

<<<<<<< HEAD
    $stderr_stream=@ssh2_fetch_stream($stdout_stream, SSH2_STREAM_STDERR);
=======
    $stderr_stream = @ssh2_fetch_stream($stdout_stream, SSH2_STREAM_STDERR);
>>>>>>> dev
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

<<<<<<< HEAD
=======

  public function checkDeamon($string)
  {
    // Return true if $string exists in ps aux command
  }

>>>>>>> dev
  public function getUptime()
  {
    $return = $this->_exec('uptime');
    $result = $this->_execResult($return[0]);

    preg_match("#averages?: ([0-9\.]+),[\s]+([0-9\.]+),[\s]+([0-9\.]+)#",$result,$avgs); 

    return array(@$avgs[1], @$avgs[2], @$avgs[3]);
  }

  public function getMemory()
  {
    $return = $this->_exec('cat /proc/meminfo');
    $result = $this->_execResult($return[0]);

    preg_match("#^MemTotal:\s*(\d+) kB\s*MemFree:\s*(\d+) kB#",$result,$mem);
    preg_match("#SwapTotal:\s*(\d+) kB\s*SwapFree:\s*(\d+) kB#",$result,$swap);

    return array(@$mem[1], @$mem[2], @$swap[1], @$swap[2]);
  }

  public function getTraffic($dev="eth0")
  {
    $return = $this->_exec('cat /proc/net/dev');
    $result = $this->_execResult($return[0]);

    preg_match("#".$dev.":\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)#",$result,$traffic);

    return array(@$traffic[1], @$traffic[9]);
  }

  public function getCpu()
  {
    $return = $this->_exec('cat /proc/stat');
    $result = $this->_execResult($return[0]);

    preg_match("#^cpu\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)#",$result,$cpu); 

    return array(@$cpu[1], @$cpu[2], @$cpu[3], @$cpu[4], @$cpu[5], @$cpu[6], @$cpu[7]);
  }
}