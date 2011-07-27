<?php

namespace Rrdtool;

Class Setup
{
  private $options = array();
  private $db_path;

  public function __construct($db_path)
  {
    $this->db_path = $db_path;
  }

  public function setOptions(array $options)
  {
    $this->options = $options;
    return $this;
  }

  function execute()
  {
    if(file_exists($this->db_path)) {
      return true;
    }

    $return = rrd_create($this->db_path, $this->options, count($this->options));

    if (!$return) {
      throw new Rrdtool_Exception("rrd_create() ERROR: " . rrd_error());
    }
    else {
      return $return;
    }
  }
}
