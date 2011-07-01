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
    return $this->twig->render('config/index.twig', array('servers' => $this->_servers));
  }
}