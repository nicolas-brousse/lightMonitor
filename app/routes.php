<?php


$app->error(function(\Exception $e) use ($app) {
    if ($e instanceof NotFoundHttpException) {
        $content = vsprintf('<h1>%d - %s (%s)</h1>', array(
           $e->getStatusCode(),
           Response::$statusTexts[$e->getStatusCode()],
           $app['request']->getRequestUri()
        ));
        return new Response($content, $e->getStatusCode());
    }
 
    if ($e instanceof HttpException) {
        return new Response('<h1>You should go eat some cookies while we\'re fixing this feature!</h1>', $e->getStatusCode());
    }
});



# require_once 'app/controllers/index.php';


# Homepage/Dashboard
$app->get('/', function () use($app, $configs) {
  return $app['twig']->render('index/index.html.php', array('servers' => $configs->servers));
})
->bind('homepage');
# $app->get('/',                    Index_Controller::Index_Action())->bind('homepage');
# $app->get('/dashboard',           Index_Controller::Index_Action())->bind('homepage');



# Servers
$app->get('/servers/{ip}', function ($ip) use ($app, $configs) {
  $ip = $app['request']->get('ip');
  return $app['twig']->render('servers/details.html.php', array(
    #'server'  => Model_Server::findByIp($ip),
  ));
})
->bind('servers');
# $app->get('/servers/{servername}',  Servers_Controller::Index_Action())->bind('servers');



# Configs
$app->get('/configs', function () use ($app, $configs) {
  return $app['twig']->render('configs/index.html.php', array('servers' => $configs->servers));
})
->bind('configs');
# $app->get('/configs',               Servers_Configs::Index_Action())->bind('configs');
# $app->get('/configs/edit/{ip}',     Servers_Configs::Edit_Action())->bind('configs');
# $app->get('/configs/delete/{ip}',   Servers_Configs::Delete_Action())->bind('configs');