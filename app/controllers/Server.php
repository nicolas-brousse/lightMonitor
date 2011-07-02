<?php

Class Server_Controller extends Controller_Base
{
  public function init()
  {
    
  }

  public function Index_Action()
  {
    $ip = $this->_getRequest()->get('ip');
    $server = $this->db->fetchAssoc(
      "SELECT * FROM servers WHERE ip = ?",
      array($ip)
    );
    $server['graphics_dir'] = '/graphs/'.md5($server['ip']).'/';

    if ($server) {
      return $this->twig->render('server/details.twig', array('server' => $server));
    }
    else {
      return $this->_halt();
      #return "Erreur à faire !";#$this->app->redirector('/home');
    }
  }
}