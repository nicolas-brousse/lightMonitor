<?php

namespace Controller\Setting;

use Controller\Base;
#use Controller\Helper\Token;

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



  private function _setPassword($pass)
  {
    #$this->password_salt = Token::get();
    #$this->password = sha1($this->password_salt . $this->password);
    #return $pass;
  }
}
