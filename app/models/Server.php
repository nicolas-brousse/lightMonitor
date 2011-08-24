<?php

Namespace Model;

use Asker\Asker;

Class Server extends Base
{
  public function findAll()
  {
    $servers = array();
    foreach ($this->db->fetchAll("SELECT * FROM servers") as $server) {
      $tmp = $server;
      $tmp['protocol'] = Asker::getProtocols($server['protocol']);
      $servers[] = $tmp;
    }
    return $servers;
  }

  public function find($ip)
  {
    $server = $this->db->fetchAssoc(
      "SELECT * FROM servers WHERE ip = ?",
      array($ip)
    );
    return $server;
  }
}