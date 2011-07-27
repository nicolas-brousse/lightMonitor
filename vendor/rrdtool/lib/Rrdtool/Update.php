<?php

namespace Rrdtool;

Class Update
{
  private $datas = array();
  private $db_path;

  public function __construct($db_path)
  {
    $this->db_path = $db_path;
  }

  public function setDatas(array $datas)
  {
    $this->datas = $datas;
    return $this;
  }

  public function execute()
  {
    if(!file_exists($this->db_path)) {
      throw new Rrdtool_Exception("Rrdtool\Update() ERROR: rdd file '".$this->db_path."' not exists");
    }

    $insert = "N";
    foreach ($this->datas as $data) {
      $insert .= ':'.$data;
    }

    $return = rrd_update($this->db_path, $insert);

    if (!$return) {
      throw new Rrdtool_Exception("rrd_update() ERROR: " . rrd_error());
    }
    else {
      return $return;
    }
  }
}
