<?php 

require_once __DIR__.'/bootstrap.php';

$app->register(new Rrdtool\RrdtoolExtension());

/**
 *
 * RRDTOOL Class
 *
 * @author Nicolas BROUSSE <pro@nicolas-brousse.fr>
 *
 **/

# view : http://www.ioncannon.net/system-administration/59/php-rrdtool-tutorial/

use Asker\Asker;
use Asker\Adaptater;
use Rrdtool\Rrdtool;

# List the servers
foreach ($app['db']->fetchAll("SELECT ip, servername FROM servers") as $server)
{
  $rrd['traffic'] = new Rrdtool($server['ip']);

  # Check if RRDTOOL Data Bases exits, else generate it
  $setup = array("--start", "N", "--step", "60",
    "DS:input:COUNTER:600:U:U",
    "DS:output:COUNTER:600:U:U",
    "RRA:AVERAGE:0.5:1:600",
    "RRA:AVERAGE:0.5:6:700",
    "RRA:AVERAGE:0.5:24:775",
    "RRA:AVERAGE:0.5:288:797",
    "RRA:MAX:0.5:1:600",
    "RRA:MAX:0.5:6:700",
    "RRA:MAX:0.5:24:775",
    "RRA:MAX:0.5:288:797",
  );
  $rrd['traffic']->setup()->setOptions($setup)->execute("traffic.rrd");

  # Ask the server to collect datas
  # $asker = new Asker(Adaptater::HTTP);
  # $asker = new Asker(Adaptater::HTTP)->setHost($host, $port);
  # $asker->getUptime();

  # Add new informations to RDDTOOL DBs
  $rrd['traffic']->update()->setDatas(array(rand(10000, 15000), rand(10000, 15000)))->execute("traffic.rrd");

  # Generates RRDTOOL Graphs
  $generate = array("--start", "-1d", "--title", "Traffic of ".$server['servername']." (average of 5min)", "--vertical-label=B/s", "--width", "500", "--height", "200",
    "DEF:inoctets=".$rrd['traffic']->getDbPath('traffic.rrd').":input:AVERAGE",
    "DEF:outoctets=".$rrd['traffic']->getDbPath('traffic.rrd').":output:AVERAGE",
    "AREA:inoctets#00FF00:In traffic",
    "LINE1:outoctets#0000FF:Out traffic\\r",
    "CDEF:inbits=inoctets,8,*",
    "CDEF:outbits=outoctets,8,*",
    "COMMENT:\\n",
    "GPRINT:inbits:AVERAGE:Avg In traffic\: %6.2lf %Sbps",
    "COMMENT:  ",
    "GPRINT:inbits:MAX:Max In traffic\: %6.2lf %Sbps\\r",
    "GPRINT:outbits:AVERAGE:Avg Out traffic\: %6.2lf %Sbps",
    "COMMENT: ",
    "GPRINT:outbits:MAX:Max Out traffic\: %6.2lf %Sbps\\r"
  );
  $rrd['traffic']->generate()->setOptions($generate)->execute("traffic-0.png");
}
