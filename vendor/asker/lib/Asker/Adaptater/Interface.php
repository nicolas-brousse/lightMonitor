<?php
/**
 *
 * Asker Extension
 * Interface for Asker Adaptater
 *
 * @package Asker Extension
 * @version 1
 * @author Nicolas BROUSSE <pro@nicolas-brousse.fr>
 **/

namespace Asker\Adaptater;

Interface Asker_Interface
{
  /**
   * 
   */
  public function getUptime();

  /**
   * @method getMemory()
   *
   * @param none
   */
  public function getMemory();

  /**
   *
   */
  public function getTraffic();

  /**
   *
   */
  public function getCpu();

  /**
   *
   */
  #public function getDiskSpace();
} // END interface 
