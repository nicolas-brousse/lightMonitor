<?php

namespace Rrdtool;

Class Update
{
  function execute($db)
  {
    $return = rrd_update($db, "N:$total_input_traffic:$total_output_traffic");

    if( $return == 0 ) {
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