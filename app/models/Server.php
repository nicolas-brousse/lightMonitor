<?php

Namespace Model;

use Asker\Asker;

Class Server extends Base
{
  protected $_tablename = 'servers';

  public function findAll()
  {
    $servers = array();
    foreach (parent::findAll() as $server) {
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

  public function insert(array $data=array())
  {
    
  }

  public function insert(array $data=array(), $id)
  {
    
  }

  public function delete($id)
  {
    parent::delete($id);
    // TODO Rrdtool->delete();
  }
}