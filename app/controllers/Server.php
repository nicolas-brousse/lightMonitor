<?php

namespace Controller;

use Asker\Asker;

Class Server extends Base
{
  public function Index_Action()
  {
    $ip = $this->_getRequest()->get('ip');
    $server = $this->db->fetchAssoc(
      "SELECT * FROM servers WHERE ip = ?",
      array($ip)
    );

    if ($server) {
      $server['graphics_dir'] = '/graphs/'.md5($server['ip']).'/';
      $server['last_check'] = '1309649892';
      $server['protocol'] = Asker::getProtocols($server['protocol']);

      $softwares = $this->db->fetchAll(
        "SELECT * FROM softwares WHERE server_id = ?",
        array($server['id'])
      );

      $server['softwares'] = $softwares;

      return $this->twig->render('server/details.twig', array('server' => $server));
    }
    else {
      # $this->monolog->addDebug(var_export($this->app->redirect('/hello'), true));
      # var_dump( $this->app->redirect('/hello')); exit;
      # get_class_methods($this->app); exit; 
      return $this->_halt();
      # return "Erreur à faire !";#$this->app->redirector('/home');
      # http://silex-project.org/doc/services.html#core-services
    }
  }
}