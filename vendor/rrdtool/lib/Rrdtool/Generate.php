<?php

namespace Rrdtool;

Class Generate
{
  private $options = array();
  private $db_path;
  private $graphics_path;

  public function __construct($graphics_path)
  {
    $this->graphics_path = $graphics_path;
  }

  public function setOptions(array $options)
  {
    $this->options = $options;
    return $this;
  }

  function execute($filename)
  {
    $return = rrd_graph($this->graphics_path.$filename, $this->options, count($this->options));

    if (!is_array($return)) {
      throw new Rrdtool_Exception("rrd_graph() ERROR: " . rrd_error());
    }
    else {
      return $return;
    }
  }
}
