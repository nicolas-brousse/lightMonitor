<?php

Class Config_Controller extends Controller_Base
{
  private $_servers = array();

  public function init()
  {
    $this->_servers = $this->db->fetchAll("SELECT * FROM servers");
  }

  public function Index_Action()
  {
    /*$limit = 20;
    $midrange = 7;
    $itemsCount = $repository->getListCount();
    $paginator = new Paginator($itemsCount, $offset, $limit, $midrange);
    $items = $repository->getList($offset);
    return array('items' => $items, 'paginator' => $paginator);*/

    return $this->twig->render('config/index.twig', array('servers' => $this->_servers));
  }

  public function New_Action()
  {
    return $this->twig->render('config/index.twig', array(
      'active_tab' => 'form',
      'form' => array('action' => $this->_getUrl('configs.save')),
    ));
  }

  public function Save_Action()
  {
    var_dump($this->_getRequest()->get('form'));
    $this->db->insert('servers', array('ip' => 'ip'));
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
        'action' => $this->_getUrl('configs.update', array('ip' => $ip)),
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
      # Ask confirmation
      try {
        $this->db->delete('servers', array('id' => $server['id']));
      }
      catch(PDOException $e) { var_dump($e->getMessage()); }
      return $this->app->redirector('/configs');
      #return redirect;
    }
  }
}