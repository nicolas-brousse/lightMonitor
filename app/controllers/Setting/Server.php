<?php

namespace Controller\Setting;

use Controller\Base;
use Controller\Helper\Krypt;
use Model\Server as Model_Server;
use Asker\Asker;

Class Server extends Base
{
  private $_servers = array();
  private $_appSshKey = array('pubkey' => null, 'privkey' => null);

  public function init()
  {
    $this->_servers = Model_Server::getInstance()->findAll();

    $this->_appSshKey['pubkey'] = @file_get_contents(APPLICATION_BASE_URI . '/data/keys/lightmonitor_dsa.pub');
    $this->_appSshKey['privkey'] = @file_get_contents(APPLICATION_BASE_URI . '/data/keys/lightmonitor_dsa');
  }

  public function Index_Action($tab='listing')
  {
    /*$limit = 20;
    $midrange = 7;
    $itemsCount = $repository->getListCount();
    $paginator = new Paginator($itemsCount, $offset, $limit, $midrange);
    $items = $repository->getList($offset);
    return array('items' => $items, 'paginator' => $paginator);*/

    return $this->twig->render('setting/server/index.twig', array(
      'active_tab' => $tab,
      'servers' => $this->_servers,
      'form' => array(
        'params' => $this->_appSshKey,
        'action' => $this->_getUrl('settings.servers.save'),
        'protocols' => array('' => '') + Asker::getProtocols(),
      ),
    ));
  }

  public function New_Action()
  {
    return $this->Index_Action('form');
  }

  public function Save_Action()
  {
    $request = $this->_getPost();
    //var_dump($query); exit;

    #$form = new Form\Setting_Server();
    #$form->set($campaign);
    #if ($form->isValid($query))

    // TODO Verif server ping and connect with Asker and protocol choosed Before Add server
    // TODO Setup Rrdtool and generate empty graphs ?

    if (empty($request['ip'])) {
      $this->_getSession()->setFlash('error', 'Form none ok! '.$request['ip'].'- '.var_export($request, true));

      unset($request['pass']);
      return $this->twig->render('setting/server/index.twig', array(
        'active_tab' => 'form',
        'form' => array(
          'action' => $this->_getUrl('settings.servers.save'),
          'protocols' => array('' => '') + Asker::getProtocols(),
          
        ) + $request,
      ));
    }
    else {
      try {
        $server = $this->db->insert('servers',
          array(
            'ip'          => $this->_getPost('ip'),
            'servername'  => $this->_getPost('servername'),
            'protocol'    => $this->_getPost('protocol'),
            'params'      => $this->_helper()->Krypt()->encrypt(serialize($this->_getPost('params'))),
            'created_at'  => time(),
            'updated_at'  => time(),
          )
        );

        $softwares = $this->db->insert('softwares', array(
          'server_id' => $this->db->lastInsertId(),
          'label' => 'ssh',
          'port' => '22',
          'created_at' => time(),
          'updated_at' => time(),
        ));
      }
      catch (\PDOException $e) {
        $this->_getSession()->setFlash('error', 'Your new server is not added! Error to insert data in db... Look <a href="https://github.com/nicolas-brousse/lightMonitor/wiki/support">support</a>'.
        (APPLICATION_ENV == 'development' ? '<br /><pre>' . $e->getMessage() . '</pre>' : ''));
      }

      if ($server && $softwares) {
        $this->_getSession()->setFlash('success', 'Your new server is added in listing!');
      }
    }
    return $this->_redirector($this->_getUrl('settings.servers'));
  }

  public function Edit_Action()
  {
    $ip = $this->_getRequest()->get('ip');
    $server = Model_Server::getInstance()->find($ip);

    if (!$server) {
      return $this->_halt();
    }

    $server['params'] = unserialize($this->_helper()->Krypt()->decrypt($server['params']));
    unset($server['params']['pass']);

    return $this->twig->render('setting/server/index.twig', array(
      'form' => $server + array(
        'action' => $this->_getUrl('settings.servers.update', array('ip' => $ip)),
        'protocols' => array('' => '') + Asker::getProtocols(),
        'id' => $server['id'],
      ),
      'active_tab' => 'form'
    ));
  }

  public function Update_Action()
  {
    $request = $this->_getPost();
    //var_dump($query); exit;

    #$form = new Form\Setting_Server();
    #$form->set($campaign);
    #if ($form->isValid($query))

    // TODO Verif server ping and connect with Asker and protocol choosed Before Add server
    // TODO Setup Rrdtool and generate empty graphs ?

    if (empty($request['ip'])) {
      $this->_getSession()->setFlash('error', 'Form none ok! '.$request['ip'].'- '.var_export($request, true));

      unset($request['pass']);
      return $this->twig->render('setting/server/index.twig', array(
        'active_tab' => 'form',
        'form' => array(
          'action' => $this->_getUrl('settings.servers.edit', array('ip' => $ip)),
          'protocols' => array('' => '') + Asker::getProtocols(),
          
        ) + $request,
      ));
    }
    else {
      try {
        $krypt = new Krypt();
        $server = $this->db->update('servers',
          array(
            'ip'          => $this->_getPost('ip'),
            'servername'  => $this->_getPost('servername'),
            'protocol'    => $this->_getPost('protocol'),
            'params'      => $krypt->encrypt(serialize($this->_getPost('params'))),
            'updated_at'  => time(),
          ),
          array('id' => $this->_getPost('id'))
        );
      }
      catch (\PDOException $e) {
        $this->_getSession()->setFlash('error', 'Your new server is not added! Error to insert data in db... Look <a href="https://github.com/nicolas-brousse/lightMonitor/wiki/support">support</a>'.
        (APPLICATION_ENV == 'development' ? '<br /><pre>' . $e->getMessage() . '</pre>' : ''));
      }

      if ($server) {
        $this->_getSession()->setFlash('success', 'Server updated!');
      }
    }
    return $this->_redirector($this->_getUrl('settings.servers'));
  }

  public function Delete_Action()
  {
    $ip = $this->_getRequest()->get('ip');
    $server = Model_Server::getInstance()->find($ip)

    if (!$server) {
      return $this->_halt();
    }

    /*
    # TODO: Ask confirmation
    if (!$this->_getRequest()->get('action_confirmation')) {
      $this->_getSession()->setFlash('action_confirmation', 'Are you sure to delete this server of the listing?');
    }
    else {
    */
      $delete = $this->db->delete('servers', array('id' => $server['id']));
      // TODO Rrdtool->delete();
      if ($delete) {
        $this->_getSession()->setFlash('success', 'Server <em>' . $server['servername'] . '[' . $server['ip'] . ']' . '</em> deleted of the listing!');
      }
      else {
        $this->_getSession()->setFlash('error', 'The server is not deleted! Error to deleted data in db... Look <a href="https://github.com/nicolas-brousse/lightMonitor/wiki/support">support</a>');
      }
    /*}*/

    return $this->_redirector($this->_getUrl('settings.servers'));
  }
}
