<?php

# Autoloader controllers
$controllers = array();
foreach (App::autoload(__DIR__ . '/controllers/') as $controller) {
  $classname=substr($controller,0,-4).'_Controller';
  $controllers[strtolower(substr($controller,0,-4))] = new $classname();
}


# ========================================================  ROUTES  ========================================================



$app->get('/',          function () use($controllers) { return $controllers['index']->Index_Action(); })->bind('homepage');
$app->get('/log',       function () use($controllers) { return $controllers['index']->Log_Action(); })->bind('log');
#$app->get('/dashboard', function () use($app) { return $app->redirect('/'); });



$app->get('/servers/{ip}',  function () use($controllers) { return $controllers['server']->Index_Action(); })->bind('servers');



$app->get('/configs',               function () use($controllers) { return $controllers['config']->Index_Action(); })->bind('configs');
$app->get('/configs/new',           function () use($controllers) { return $controllers['config']->New_Action(); })->bind('configs.new');
$app->post('/configs/save',         function () use($controllers) { return $controllers['config']->Save_Action(); })->bind('configs.save');
$app->get('/configs/edit/{ip}',     function () use($controllers) { return $controllers['config']->Edit_Action(); })->bind('configs.edit');
$app->post('/configs/update/{ip}',  function () use($controllers) { return $controllers['config']->Update_Action(); })->bind('configs.update');
$app->get('/configs/delete/{ip}',   function () use($controllers) { return $controllers['config']->Delete_Action(); })->bind('configs.delete');



# ============================================================================================================================

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
