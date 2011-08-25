<?php

Namespace Model;

use Asker\Asker;

Class Software extends Base
{
  protected $_tablename = 'sofwares';

  public function findAll($server_id=null)
  {
    if (intval($server_id)) {
      $this->db->fetchAll("SELECT * FROM softwares WHERE server_id = ?", array($server_id));
    }
    else {
      $this->db->fetchAll("SELECT * FROM softwares");
    }
  }
}