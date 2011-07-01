<?php

namespace Asker\Adaptater;

Class Snmp extends Base
{
  public function __construct()
  {
    if (!class_exists('SNMP')) {
      throw new Asker_Exception("ERROR: To use SNMP protocol, install php5_snmp PHP extention !");
    }
    parent::__construct();

    /*$host = "shell.example.com";
    $port = 22;
    $this->connection = ssh2_connect($host, $port);
    if (!$this->connection) {
      throw new Asker_Exception("ERROR: SSH Connection to '$host:$port' failed !");
    }
    if (ssh2_auth_password($this->connection, 'username', 'secret')) {
      throw new Asker_Exception('ERROR: SSH Connection failed, invalid username or password !');
    }*/
  }

  public function uptime()
  {

  }

  public function memory()
  {

  }

  public function trafic()
  {

  }

  public function cpu()
  {

  }
}