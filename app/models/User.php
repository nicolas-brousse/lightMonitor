<?php

Namespace Model;

use Asker\Asker;
use Controller\Helper\Token;

Class User extends Base
{
  protected $_tablename = 'users';


  private function _setPassword($password)
  {
    $password_salt = Token::get();
    $password = sha1($this->password_salt . $password);
    return array($password_salt, $password);
  }
}