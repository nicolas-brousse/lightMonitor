<?php

namespace Rrdtool;

require_once 'Exception.php';

Class Rrdtool
{
  function __construct()
  {
    if (!function_exists('rrd_graph') OR !function_exists('rrd_create') OR !function_exists('rrd_update')) {
      throw new Rrdtool_Exception('ERROR: Rrdtool PHP extension is not installed !');
    }
  }

  public function update()
  {
    return new Update();
  }

  public function generate()
  {
    return new Generate();
  }

  public function setup()
  {
    return new Setup();
  }
}