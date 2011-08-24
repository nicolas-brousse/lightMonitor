<?php 

require_once __DIR__.'/bootstrap.php';

use Symfony\Component\HttpFoundation\Response;

/**
 *
 * Check status of servers
 *
 * @author Nicolas BROUSSE <pro@nicolas-brousse.fr>
 *
 **/


# List the servers

# Ping it
# If don't response, add servername to queue

define('TIMEOUT', 5);
$serveurs_without_response = array();

#$serveurs = Model_Server::getInstance()->findAll();
foreach ($app['db']->fetchAll("SELECT * FROM servers") as $server)
{
  #$softwares = Model_Sofware::find($server['id'])

  exec("ping -c 4 -t ".TIMEOUT." '".$server['ip']."'", $output, $response);

  if ($response != 0) {
    $serveurs_without_response[] = $server['ip'];
    #Model\Log::add($server['id'], 'Ping no response');
    #Model\Software::update($serverID, $softwareID, false);
  }

  # Check port
  /*******/
  foreach ($app['db']->fetchAll("SELECT * FROM softwares WHERE ?", array($server['id'])) as $software)
  {
    $fp = fsockopen($server['ip'], $software['port'], $errno, $errstr, TIMEOUT);
    fclose($fp);
  }

  # Prevent by mail if software change state

  // TODO: can check software (need SSH connection ?)
}




# If servername queue is not empty, send an email to contact
if (!empty($serveurs_without_response)) {
  $message = \Swift_Message::newInstance()
      ->setSubject('[LightMonitor] Problème sur un serveur')
      ->setFrom(array( $config->app['monitor']['email'] ))
      ->setTo(array( $config->app['monitor']['email'] ))
      ->setBody("Un problème est survenu sur les IPs suivantes : ".var_export($serveurs_without_response, true));

  $transport = \Swift_MailTransport::newInstance();
  $mailer = \Swift_Mailer::newInstance($transport);
  if ( !$mailer->send($message) ) {
    echo "Mailer Error";
  }
}

$app['monolog']->addInfo(basename(__FILE__) . " script execute in " . (microtime(true) - APPLICATION_MICROTIME_START) . " secondes");