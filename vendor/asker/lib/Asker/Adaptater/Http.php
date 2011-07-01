<?php

namespace Asker\Adaptater;

Class Http extends Base
{
  public function __construct()
  {
    if (!function_exists('curl_init')) {
      throw new Asker_Exception("ERROR: To use HTTP protocol, install php5_curl PHP extention !");
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