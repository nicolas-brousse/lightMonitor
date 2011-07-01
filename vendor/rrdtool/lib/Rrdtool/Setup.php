<?php

namespace Rrdtool;

Class Setup extends Base
{
  private $options = array();

  public function setOptions(array $options)
  {
    $this->options = $options;
  }

  function execute($db)
  {
    $return = rrd_create($db, $this->options, count($this->options));

    if( $return == 0 ) {
      throw new Rrdtool_Exception("rrd_create() ERROR: " . rrd_error());
    }
    else {
      return $return;
    }
  }
}

/*
$fname = "trafic.rrd";

$opts = array( "–step", "300", "–start", 0,
         "DS:input:COUNTER:600:U:U",
         "DS:output:COUNTER:600:U:U",
         "RRA:AVERAGE:0.5:1:600",
         "RRA:AVERAGE:0.5:6:700",
         "RRA:AVERAGE:0.5:24:775",
         "RRA:AVERAGE:0.5:288:797",
         "RRA:MAX:0.5:1:600",
         "RRA:MAX:0.5:6:700",
         "RRA:MAX:0.5:24:775",
         "RRA:MAX:0.5:288:797"
      );

$ret = rrd_create($fname, $opts, count($opts));

if( $ret == 0 )
{
  $err = rrd_error();
  echo "Create error: $err\n";
}
*/