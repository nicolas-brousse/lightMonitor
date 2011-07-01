<?php

Abstract Class Controller_Base
{

  abstract function init();


  protected $app;
  protected $twig;
  protected $db;

  final public function __construct()
  {
    $this->app = App::getApp();
    $this->twig = $this->app['twig'];
    $this->db = $this->app['db'];
    #var_dump($this->app); exit;

    $this->init();
  }

  protected function _getRequest()
  {
    return $this->app['request'];
  }

  protected function _halt()
  {
    throw new Symfony\Component\HttpKernel\Exception\NotFoundHttpException();
  }
}