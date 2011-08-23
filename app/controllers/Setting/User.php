<?php

namespace Controller\Setting;

use Controller\Base;
use Asker\Asker;

Class User extends Base
{
  private $_users = array();

  public function init()
  {
    
  }

  public function Index_Action()
  {
    return $this->twig->render('setting/user/index.twig', array(
      'users' => $this->_users,
      'form' => array(
        #'action' => $this->_getUrl('settings.users.save'),
      ),
    ));
  }
}
