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
    return $this->twig->render('config/index.twig', array('servers' => $this->_servers));
  }

  public function Save_Action()
  {
    
  }

  public function Edit_Action()
  {
    return $this->twig->render('config/index.twig', array('servers' => $this->_servers));
  }

  public function Update_Action()
  {
    return $this->twig->render('config/index.twig', array('servers' => $this->_servers));
  }

  public function Delete_Action()
  {
    return $this->twig->render('config/index.twig', array('servers' => $this->_servers));
  }
}