<?php

namespace Rrdtool;

require_once 'Exception.php';

Class Rrdtool
{
  protected $ip;
  protected $server_dir;
  protected $server_path;
  protected $db_path;

  function __construct($ip=false)
  {
    if (!function_exists('rrd_graph') OR !function_exists('rrd_create') OR !function_exists('rrd_update')) {
      throw new Rrdtool_Exception('ERROR: Rrdtool PHP extension is not installed !');
    }
    if (empty($servername)) {
      throw new Rrdtool_Exception('ERROR: servername must be specified !');
    }

    $this->dir_db = __DIR__ . '/../../../../data/rrdtool/'. md5($servername).'/';
    $this->ip = $ip;
    $this->server_dir = md5($ip);
    $this->server_path = __DIR__ . '/../../../../';
  }

  public function getGraphPath($filename=false)
  {
    return $this->server_path.$this->server_dir.'/'.($filename ? $filename.'/' : '');
  }

  public function getDbPath($filename=false)
  {
    return $this->db_path.$this->server_dir.'/'.($filename ? $filename.'/': '');
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

  public function cleanDir($dir)
  {
    
  }
}