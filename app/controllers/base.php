<?php

Abstract Class Controller_Base
{

  abstract function init();


  protected $app;
  protected $twig;
  protected $db;
  protected $monolog;
  protected $url_generator;

  final public function __construct()
  {
    $this->app = App::getApp();
    $this->twig = $this->app['twig'];
    $this->db = $this->app['db'];
    $this->monolog = $this->app['monolog'];
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

  protected function _helper()
  {
    
  }

  protected function _getUrl($bind, array $parameters=array())
  {
    return $this->app['url_generator']->generate($bind, $parameters);
  }
}