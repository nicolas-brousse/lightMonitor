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

$serveurs_without_response = array();

#$serveurs = Model_Server::findAll();
foreach ($app['db']->fetchAll("SELECT * FROM servers") as $server)
{
  #$softwares = Model_Sofware::find($server['id'])

  #print("ping -c 4 ".$server['ip']);
  exec("ping -c 4 '".$server['ip']."'", $output, $response);

  if ($response != 0) {
    $serveurs_without_response[] = $server['ip'];
    #Model_Log::add($server['id'], 'Ping no response');
    #Model_Software::update($serverID, $softwareID, false);
  }
}

# If servername queue is not empty, send an email to hostmaster
if (!empty($serveurs_without_response)) {

  $message = \Swift_Message::newInstance()
      ->setSubject('[LightMonitor] Problème sur un serveur')
      ->setFrom(array('noreply@lightmonitor.com'))
      ->setTo(array( $configs->app['monitor']['email'] ))
      ->setBody("Un problème est survenu sur les IPs suivantes : ".var_export($serveurs_without_response, true));

  $transport = \Swift_MailTransport::newInstance();
  $mailer = \Swift_Mailer::newInstance($transport);
  if ( !$mailer->send($message) ) {
    echo "Mailer Error";
  }
}