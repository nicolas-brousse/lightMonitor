<?php

namespace Asker\Adaptater;

/**
 * @version 1
 */
Interface Asker_Interface
{
  /**
   *
   */
  public function getUptime();

  /**
   *
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
}