<?php

namespace Controller;

use Asker\Asker;

Class Config extends Base
{
  private $_servers = array();

  public function init()
  {
    $servers = array();
    foreach ($this->db->fetchAll("SELECT * FROM servers") as $server) {
      $tmp = $server;
      $tmp['protocol'] = Asker::getProtocols($server['protocol']);
      $servers[] = $tmp;
    }
    $this->_servers = $servers;
  }

  public function Index_Action()
  {
    /*$limit = 20;
    $midrange = 7;
    $itemsCount = $repository->getListCount();
    $paginator = new Paginator($itemsCount, $offset, $limit, $midrange);
    $items = $repository->getList($offset);
    return array('items' => $items, 'paginator' => $paginator);*/

    return $this->twig->render('config/index.twig', array(
      'servers' => $this->_servers,
      'form' => array(
        'action' => $this->_getUrl('configs.servers.save'),
        'protocols' => Asker::getProtocols(),
      ),
    ));
  }

  public function New_Action()
  {
    return $this->twig->render('config/index.twig', array(
      'active_tab' => 'form',
      'form' => array(
        'action' => $this->_getUrl('configs.save'),
        'protocols' => Asker::getProtocols(),
      ),
    ));
  }

  public function Save_Action()
  {
    //var_dump($this->_getRequest()->get('form'));
    $this->db->insert('servers',
      array(
        'ip' => '192.168.1.200',
        'servername' => 'srv-home',
        'protocol' => 10,
        'created_at' => time(),
        'updated_at' => time(),
      )
    );
    return $this->_redirector('/configs/servers');
  }

  public function Edit_Action()
  {
    $ip = $this->_getRequest()->get('ip');
    $server = $this->db->fetchAssoc(
      "SELECT * FROM servers WHERE ip = ?",
      array($ip)
    );
    if (!$server) {
      return $this->_halt();
    }

    return $this->twig->render('config/index.twig', array(
      'form' => $server + array(
        'action' => $this->_getUrl('configs.servers.update', array('ip' => $ip)),
        'protocols' => Asker::getProtocols(),
      ),
      'active_tab' => 'form'
    ));
  }

  public function Update_Action()
  {
    var_dump($this->_getRequest()->get('form')); exit;
    $conn->update('servers', array('ip' => 'ip'), array('id' => $id));
    return $this->twig->render('config/index.twig', array('servers' => $this->_servers, 'active_tab' => 'form'));
  }

  public function Delete_Action()
  {
    $ip = $this->_getRequest()->get('ip');
    $server = $this->db->fetchAssoc(
      "SELECT id FROM servers WHERE ip = ?",
      array($ip)
    );
    if (!$server) {
      return $this->_halt();
    }
    else {
      # TODO: Ask confirmation
      $this->db->delete('servers', array('id' => $server['id']));
      return $this->_redirector('/configs/servers');
      #return redirect;
    }
  }
}