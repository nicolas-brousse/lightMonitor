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
    if (empty($ip)) {
      throw new Rrdtool_Exception('ERROR: servername must be specified !');
    }

    // TODO verif IP value

    // TODO use configs values
    $this->db_path = __DIR__ . '/../../../../data/rrdtool/';
    $this->ip = $ip;
    $this->server_dir = md5($ip);
    $this->server_path = __DIR__ . '/../../../../';

    # Create repository
    if(!file_exists($this->db_path.$this->server_dir.'/')) {
      mkdir($this->db_path.$this->server_dir.'/', 0777, true);
    }
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
    return new Setup($this->getDbPath());
  }

  public function cleanDir($dir)
  {
    
  }
}