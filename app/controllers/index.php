<?php

Class Index_Controller extends Controller_Base
{
  function init()
  {
    
  }

  static function Index_Action()
  {
    global $app;

    return $app['twig']->render('index/index.html.php', array());
  }
}