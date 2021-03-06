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

Namespace Asker\Adapter;

require_once 'Interface.php';

Abstract Class Base implements Asker_Interface
{
  protected $_config;

  final public function __construct($config)
  {
    $this->_verifDependencies();
    $this->_config = $config;
    $this->init();
  }

  final public function getParamsStructure()
  {
    return $this->_paramsStructure;
  }

  protected $_paramsStructure = array();

  protected function _verifDependencies()   { }

  public function init()                    { }

  public function getUptime()               { }

  public function getMemory()               { }

  public function getTraffic($dev="eth0")   { }

  public function getCpu()                  { }
}