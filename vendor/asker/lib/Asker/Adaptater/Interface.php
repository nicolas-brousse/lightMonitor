<?php

namespace Asker\Adaptater;

Interface Asker_Interface
{
  public function uptime();
  public function memory();
  public function trafic();
  public function cpu();
  #public function disk_space();
}