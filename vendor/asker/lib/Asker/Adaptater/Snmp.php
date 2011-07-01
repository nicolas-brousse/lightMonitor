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