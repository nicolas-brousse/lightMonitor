<?php

Namespace Controller\Helper;

use App;

Class Token extends Base
{
  public static function get($length = 40)
  {
    $token = sha1(microtime() . uniqid(mt_rand(), true));

    return substr($token, 0, $length);
  }
}