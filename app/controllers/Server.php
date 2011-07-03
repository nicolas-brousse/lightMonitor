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
    $server['last_check'] = '1309649892';

    $server['softwares'] = array(
      array(
        'status' => true,
        'name'   => 'Ping',
        'updated_at'  => '1309649892',
      ),
      array(
        'status' => false,
        'name'   => 'DNS',
        'port'   => '53',
        'updated_at'  => '1309649902',
      ),
      array(
        'status' => false,
        'name'   => 'SSH',
        'port'   => '22',
        'updated_at'  => '1309649902',
      ),
      array(
        'status' => true,
        'name'   => 'FTP',
        'port'   => '21',
        'updated_at'  => '1309649992',
      ),
    );

    if ($server) {
      return $this->twig->render('server/details.twig', array('server' => $server));
    }
    else {
      return $this->_halt();
      #return "Erreur à faire !";#$this->app->redirector('/home');
    }
  }
}