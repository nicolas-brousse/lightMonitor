<?php

Class Index_Controller extends Controller_Base
{
  private $_servers = array();

  public function init()
  {
    $this->_servers = $this->db->fetchAll("SELECT * FROM servers");
  }

  public function Index_Action()
  {
    return $this->twig->render('index/index.twig', array('servers' => $this->_servers));
  }

  public function Log_Action()
  {
    return $this->twig->render('index/index.twig', array('servers' => $this->_servers, 'active_tab' => 'log'));
  }
}