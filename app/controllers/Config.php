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

  public function Create_Action()
  {
    return $this->twig->render('config/index.twig', array('server' => $server, 'active_tab' => 'form'));
  }

  public function Save_Action()
  {
    
  }

  public function Edit_Action()
  {
    $ip = $this->_getRequest()->get('ip');
    $server = $this->db->fetchAssoc(
      "SELECT * FROM servers WHERE ip = ?",
      array($ip)
    );
    return $this->twig->render('config/index.twig', array('server' => $server, 'active_tab' => 'form'));
  }

  public function Update_Action()
  {
    return $this->twig->render('config/index.twig', array('servers' => $this->_servers, 'active_tab' => 'form'));
  }

  public function Delete_Action()
  {
    return $this->twig->render('config/index.twig', array('servers' => $this->_servers));
  }
}