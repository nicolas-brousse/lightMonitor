<?php

namespace Rrdtool;

require_once 'Exception.php';

Class Rrdtool
{
  protected $ip;
  protected $filename;
  protected $server_dir;
  protected $graphics_path;
  protected $db_path;

  function __construct($ip=false, $filename=false)
  {
    if (!function_exists('rrd_graph') OR !function_exists('rrd_create') OR !function_exists('rrd_update')) {
      throw new Rrdtool_Exception('ERROR: Rrdtool PHP extension is not installed !');
    }
    if (empty($ip)) {
      throw new Rrdtool_Exception('ERROR: $ip must be specified !');
    }
    if (empty($filename)) {
      throw new Rrdtool_Exception('ERROR: $filename must be specified !');
    }

    // TODO verif IP value

    // TODO use configs app values
    $this->db_path = __DIR__ . '/../../../../data/rrdtool/';
    $this->ip = $ip;
    $this->filename = $filename;
    $this->server_dir = md5($ip);
    $this->graphics_path = __DIR__ . '/../../../../public/graphs/';

    # Create repositories
    if(!file_exists($this->db_path.$this->server_dir.'/')) {
      mkdir($this->db_path.$this->server_dir.'/', 0777, true);
    }
    if(!file_exists($this->graphics_path.$this->server_dir.'/')) {
      mkdir($this->graphics_path.$this->server_dir.'/', 0777, true);
    }
  }

  public function getGraphPath($filename=false)
  {
    return $this->graphics_path.$this->server_dir.'/'.($filename ? $filename : '');
  }

  public function getDbPath()
  {
    return $this->db_path.$this->server_dir.'/'.$this->filename;
  }

  public function update()
  {
    return new Update($this->getDbPath());
  }

  public function generate()
  {
    return new Generate($this->getGraphPath());
  }

  public function setup()
  {
    return new Setup($this->getDbPath());
  }

  public function cleanDir($dir)
  {
    
  }

  public function delete()
  {
    // TODO remove dirs
  }
}