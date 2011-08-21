<?php
/**
 *
 * Asker Extension
 * Base Asker Adapter
 *
 * @package Asker Extension
 * @version 1
 * @author Nicolas BROUSSE <pro@nicolas-brousse.fr>
 */

namespace Asker\Adapter;

require_once 'Interface.php';

Abstract Class Base implements Asker_Interface
{
  protected $_config;

  final public function __construct($config)
  {
    $this->_config = $config;
    $this->init();
  }

  final public function getParamsStructure()
  {
    return $this->_paramsStructure;
  }

  protected $_paramsStructure = array();

  public function getUptime()               { return; }

  public function getMemory()               { return; }

  public function getTraffic($dev="eth0")   { return; }

  public function getCpu()                  { return; }
}