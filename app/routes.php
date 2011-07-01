<?php

# require_once 'app/controllers/index.php';


# Homepage/Dashboard
$app->get('/', function () use($app) {
  return $app['twig']->render('index/index.html.php', array());
})
->bind('homepage');
# $app->get('/',                    Index_Controller::Index_Action())->bind('homepage');
# $app->get('/dashboard',           Index_Controller::Index_Action())->bind('homepage');



# Servers
$app->get('/servers/{servername}', function ($servername) use ($app) {
  $servername = $app['request']->get('servername');
  return $app['twig']->render('servers/details.html.php', array());
})
->bind('servers');
# $app->get('/servers/{servername}',  Servers_Controller::Index_Action())->bind('servers');



# Configs
$app->get('/configs', function () use ($app) {
  $servers = array(
    array(
      'servername'  => 'Serveur 1',
      'ip'          => '10.0.0.1',
      'protocol'    => 'SNMP',
    ),
    array(
      'servername'  => 'Serveur 2',
      'ip'          => '10.0.0.2',
      'protocol'    => 'SSH',
    ),
  );
  return $app['twig']->render('configs/index.html.php', array('servers' => $servers));
})
->bind('configs');
# $app->get('/configs',                 Servers_Configs::Index_Action())->bind('configs');
# $app->get('/configs/edit/id/{id}',    Servers_Configs::Edit_Action())->bind('configs');
# $app->get('/configs/delete/id/{id}',  Servers_Configs::Delete_Action())->bind('configs');