<?php

# require_once 'app/controllers/index.php';
# $app->get('/', Index_Controller::Index_Action())->bind('homepage');


# Homepage/Dashboard
$app->get('/', function () use($app) {
  return $app['twig']->render('index/index.html.php', array());
})
->bind('homepage');


# Servers
$app->get('/servers/{servername}', function ($servername) use ($app) {
  $servername = $app['request']->get('servername');
  return $app['twig']->render('servers/details.html.php', array());
})
->bind('servers');



# Configs
$app->get('/configs', function () use ($app) {
  return $app['twig']->render('configs/index.html.php', array());
})
->bind('configs');