<?php
/**
 *
 * Asker Extension
 * Adaptater : SSH
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
  private $_login;
  private $_pass;

  public function init()
  {
    if (!function_exists('ssh2_connect')) {
      throw new Asker_Adaptater_Exception("ERROR: To use SSH protocol, install php5-ssh2 PHP extention !");
    }
  }

  public function __destruct()
  {
    $this->_logout();
  }

  private function _getConnection()
  {
    // TODO set Host and Log
    // TODO Can Log with public key
    if (!$this->_connection) {
      $this->_connection = ssh2_connect($this->_host, $this->_port);
      if (!$this->_connection) {
        throw new Asker_Adaptater_Exception("ERROR: SSH Connection to '$host:$port' failed !");
      }
      if (ssh2_auth_password($this->_connection, $this->_login, $this->_pas)) {
        throw new Asker_Adaptater_Exception('ERROR: SSH Connection failed, invalid username or password !');
      }
    }
    else {
      return $this->_connection;
    }
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

  public function setAuth($login, $pass)
  {
    $this->_login = $login;
    $this->_pass = $pass;
    return $this;
  }

  public function getUptime()
  {
    $return = $this->_exec('uptime');
    return $this->_execResult($return);
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