<?php
/**
 *
 * Asker Extension
 * Interface for Asker Adapter
 *
 * @package Asker Extension
 * @version 1
 * @author Nicolas BROUSSE <pro@nicolas-brousse.fr>
 **/

namespace Asker\Adapter;

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
