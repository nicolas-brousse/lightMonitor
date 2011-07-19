<?php

# Autoloader controllers
$controllers = array();
foreach (App::autoload(__DIR__ . '/controllers/') as $controller) {
  $classname=substr($controller,0,-4).'_Controller';
  $controllers[strtolower(substr($controller,0,-4))] = new $classname();
}

// TODO: use PHP5 autoloader


# ========================================================  ROUTES  ========================================================



$app->get('/',          function () use($controllers) { return $controllers['index']->Index_Action(); })->bind('homepage');
$app->get('/log',       function () use($controllers) { return $controllers['index']->Log_Action(); })->bind('log');
#$app->get('/dashboard', function () use($app) { return $app->redirect('/'); });



$app->get('/servers/{ip}',  function () use($controllers) { return $controllers['server']->Index_Action(); })->bind('servers');



$app->get('/configs/servers',               function () use($controllers) { return $controllers['config']->Index_Action(); })->bind('configs.servers');
$app->get('/configs/servers/new',           function () use($controllers) { return $controllers['config']->New_Action(); })->bind('configs.servers.new');
$app->post('/configs/save',         function () use($controllers) { return $controllers['config']->Save_Action(); })->bind('configs.servers.save');
$app->get('/configs/edit/{ip}',     function () use($controllers) { return $controllers['config']->Edit_Action(); })->bind('configs.servers.edit');
$app->post('/configs/update/{ip}',  function () use($controllers) { return $controllers['config']->Update_Action(); })->bind('configs.servers.update');
$app->get('/configs/delete/{ip}',   function () use($controllers) { return $controllers['config']->Delete_Action(); })->bind('configs.servers.delete');

$app->get('/configs/users',               function () use($controllers) { return $controllers['config']->Index_Action(); })->bind('configs.users');



# ============================================================================================================================

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

$app->get('/favicon.png', function() use ($app) {
  $state = array_rand(array('on' => true, 'off' => true));
  return new Response(
          file_get_contents(APPLICATION_BASE_URI . 'public/img/icons/'.$state.'.png'),
          200,
          array(
            'Content-Type' => 'image/png',
            'Cache-Control' => 'no-cache, must-revalidate',
            'Expires' => ' Sat, 26 Jul 1997 05:00:00 GMT',
          )
      );
});
$app->get('/favicon.ico', function() use ($app) {
  $state = array_rand(array('on' => true, 'off' => true));
  return new Response(
          file_get_contents(APPLICATION_BASE_URI . 'public/img/icons/favicon_'.$state.'.ico'),
          200,
          array(
            'Content-Type' => 'image/png',
            'Cache-Control' => 'no-cache, must-revalidate',
            'Expires' => ' Sat, 26 Jul 1997 05:00:00 GMT',
          )
      );
});

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
