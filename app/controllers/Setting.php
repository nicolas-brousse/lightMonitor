<?php

namespace Controller;

use Asker\Asker;

Class Setting extends Base
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

    return $this->twig->render('setting/index.twig', array(
      'servers' => $this->_servers,
      'form' => array(
        'action' => $this->_getUrl('settings.servers.save'),
        'protocols' => array('' => '') + Asker::getProtocols(),
      ),
    ));
  }

  public function New_Action()
  {
    return $this->twig->render('setting/index.twig', array(
      'active_tab' => 'form',
      'form' => array(
        'action' => $this->_getUrl('settings.servers.save'),
        'protocols' => array('' => '') + Asker::getProtocols(),
      ),
    ));
  }

  public function Save_Action()
  {
    $request = $this->_getPost();
    //var_dump($query); exit;

    #$form = new Form\Setting_Server();
    #$form->set($campaign);
    #if ($form->isValid($query))

    if (empty($request['ip'])) {
      $this->_getSession()->setFlash('error', 'Form none ok! '.$request['ip'].'- '.var_export($request, true));

      return $this->twig->render('setting/index.twig', array(
        'active_tab' => 'form',
        'form' => array(
          'action' => $this->_getUrl('settings.servers.save'),
          'protocols' => array('' => '') + Asker::getProtocols(),
          
        ) + $request,
      ));
    }
    else {
      $this->db->delete('servers', array('id' => '0'));

      $server = $this->db->insert('servers',
        array(
          'id'          => '0',
          'ip'          => $this->_getPost('ip'),
          'servername'  => $this->_getPost('servername'),
          'protocol'    => $this->_getPost('protocol'),
          'port'        => $this->_getPost('port'),
          'login'       => $this->_getPost('login'),
          'pass'        => $this->_getPost('pass'),
          'created_at'  => time(),
          'updated_at'  => time(),
        )
      );
      var_dump($insert); exit;
      // $softwares = $this->db->insert('softwares', array('server_id' => $server->id, 'name' => ));

      if ($server && $softwares) {
        $this->_getSession()->setFlash('success', 'Your new server is added in listing!');
      }
      else {
        $this->_getSession()->setFlash('error', 'Your new server is not added! Error to insert data in db... Look <a href="https://github.com/nicolas-brousse/lightMonitor/wiki/support">support</a>');
      }
    }
    return $this->_redirector($this->_getUrl('settings.servers'));
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

    return $this->twig->render('setting/index.twig', array(
      'form' => $server + array(
        'action' => $this->_getUrl('settings.servers.update', array('ip' => $ip)),
        'protocols' => Asker::getProtocols(),
      ),
      'active_tab' => 'form'
    ));
  }

  public function Update_Action()
  {
    var_dump($this->_getRequest()->get('form')); exit;
    $conn->update('servers', array('ip' => 'ip'), array('id' => $id));
    return $this->twig->render('setting/index.twig', array('servers' => $this->_servers, 'active_tab' => 'form'));
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

    # TODO: Ask confirmation
    if (!$this->_getRequest()->get('action_confirmation')) {
      $this->_getSession()->setFlash('action_confirmation', 'Are you sure to delete this server of the listing?');
    }
    else {
      #$this->db->delete('servers', array('id' => $server['id']))
      if (true) {
        $this->_getSession()->setFlash('success', 'Server deleted of the listing!');
      }
      else {
        $this->_getSession()->setFlash('error', 'The server is not deleted! Error to deleted data in db... Look <a href="https://github.com/nicolas-brousse/lightMonitor/wiki/support">support</a>');
      }
    }

    return $this->_redirector($this->_getUrl('settings.servers'));
  }
}