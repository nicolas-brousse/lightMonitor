<?php

namespace Asker\Adaptater;

Class Ssh extends Base
{
  public function __construct()
  {
    if (!function_exists('ssh2_connect')) {
      throw new Asker_Exception("ERROR: To use SSH protocol, install php5_ssh2 PHP extention !");
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