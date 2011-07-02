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

  public function execute($db)
  {
    if(!file_exists($this->db_path.$db)) {
      throw new Rrdtool_Exception("Rrdtool\Update() ERROR: rdd file '".$db."' not exists");
    }

    $insert = "N";
    foreach ($this->datas as $data) {
      $insert .= ':'.$data;
    }

    $return = rrd_update($this->db_path.$db, $insert);

    if (!$return) {
      throw new Rrdtool_Exception("rrd_update() ERROR: " . rrd_error());
    }
    else {
      return $return;
    }
  }
}
