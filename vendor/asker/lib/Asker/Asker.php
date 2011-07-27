<?php
/**
 *
 * Asker
 * Entering class
 *
 * @package Asker Extension
 * @version 1
 * @author Nicolas BROUSSE <pro@nicolas-brousse.fr>
 **/

namespace Asker;

require_once 'Exception.php';

Class Asker
{
  private $_adaptater;

  public function __construct($protocol)
  {
    $this->_adaptater = new Adaptater($protocol);
    # $this->_adaptater = Adaptater::getInstance($protocol);
    if ($this->_adaptater)
      return $this->_adaptater;
    return false;
  }

  public static function getProtocols($protocol=null)
  {
    return Adaptater::getProtocols($protocol);
  }
}
