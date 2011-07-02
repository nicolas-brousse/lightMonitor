<?php

namespace Rrdtool;

Class Update extends Base
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

/*
$fname = "net.rrd";

  $total_input_traffic = 0;
  $total_output_traffic = 0;

  while(true)
  {
    $total_input_traffic += rand(10000, 15000);
    $total_output_traffic += rand(10000, 30000);

    echo time() . ": " . $total_input_traffic . " and " . $total_output_traffic . "\n";

    $ret = rrd_update($fname, "N:$total_input_traffic:$total_output_traffic");

    if( $ret == 0 )
    {
      $err = rrd_error();
      echo "ERROR occurred: $err\n";
    }

    sleep(300);
  }
*/