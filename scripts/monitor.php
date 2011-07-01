<?php 

require_once __DIR__.'/bootstrap.php';

/**
 *
 * Check status of servers
 *
 **/


# List the servers

# Ping it
# If don't response, add servername to queue

$serveurs_without_response = array();

#$serveurs = Model_Server::findAll();
foreach ($configs->servers as $server)
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
  print("Send an email !".var_export($serveurs_without_response));
}