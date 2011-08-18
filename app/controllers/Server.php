<?php

namespace Controller;

use Asker\Asker;

Class Server extends Base
{
  public function Index_Action()
  {
    $ip = $this->_getRequest()->get('ip');
    $server = $this->db->fetchAssoc(
      "SELECT * FROM servers WHERE ip = ?",
      array($ip)
    );

    if ($server) {
      $server['graphics_dir'] = '/graphs/'.md5($server['ip']).'/';
      $server['protocol'] = Asker::getProtocols($server['protocol']);

      $softwares = $this->db->fetchAll(
        "SELECT * FROM softwares WHERE server_id = ?",
        array($server['id'])
      );

      $server['softwares'] = $softwares;

      return $this->twig->render('server/details.twig', array('server' => $server));
    }

    return $this->_halt();
  }
}