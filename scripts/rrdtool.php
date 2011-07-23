<?php 

require_once __DIR__.'/bootstrap.php';

$app->register(new Rrdtool\RrdtoolExtension());

/**
 *
 * RRDTOOL Graphs generator
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
  $rrd['uptime'] = new Rrdtool($server['ip']);

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
  $setup = array("--start", "N", "--step", "60",
    "DS:uptime1:GAUGE:600:0:90",
    "DS:uptime5:GAUGE:600:0:90",
    "DS:uptime15:GAUGE:600:0:90",
    "RRA:MIN:0.5:12:1440",
    "RRA:MAX:0.5:12:1440",
    "RRA:AVERAGE:0.5:1:1440",
  );
  $rrd['uptime']->setup()->setOptions($setup)->execute("uptime.rrd");

  # Ask the server to collect datas
  # $asker = new Asker(Adaptater::SSH);
  $asker = new Asker(Adaptater::SSH);
  #var_dump(Adaptater::SSH)
  #var_dump(get_class_methods($asker));
  #var_dump(get_class($asker)); exit;
  #$asker->setHost("ip", "port")->setAuth("root", "pass");
  #var_dump($asker->getUptime()); exit;
  # $asker->getUptime();

  # Add new informations to RDDTOOL DBs
  $rrd['traffic']->update()->setDatas(array(rand(10000, 15000), rand(10000, 15000)))->execute("traffic.rrd");
  $rrd['uptime']->update()->setDatas(array(rand(0, 2), rand(0, 2), rand(0, 2)))->execute("uptime.rrd");

  # Generates RRDTOOL Graphs
  $generate = array("--start", "-1d", "--title", "Traffic of ".$server['servername']." (average of 5min)", "--vertical-label=B/s", "--width", "500", "--height", "200",
    "DEF:inoctets=".$rrd['traffic']->getDbPath('traffic.rrd').":input:AVERAGE",
    "DEF:outoctets=".$rrd['traffic']->getDbPath('traffic.rrd').":output:AVERAGE",
    "CDEF:outoctets_line=outoctets,-1,*",
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
  $rrd['traffic']->generate()->setOptions($generate)->execute("memory-0.png");
  $generate = array("--start", "-1d", "--title", "Uptime of ".$server['servername']." (average of 5min)", "--vertical-label=uptime", "--width", "500", "--height", "200", "-l", "0",
    "DEF:uptime1=".$rrd['traffic']->getDbPath('uptime.rrd').":uptime1:AVERAGE",
    "DEF:uptime5=".$rrd['traffic']->getDbPath('uptime.rrd').":uptime5:AVERAGE",
    "DEF:uptime15=".$rrd['traffic']->getDbPath('uptime.rrd').":uptime15:AVERAGE",
    "AREA:uptime1#ffe000:uptime (1min)",
    "AREA:uptime5#ffa000:uptime (5min)",
    "AREA:uptime15#ff3333:uptime (15min)",
  );
  $rrd['uptime']->generate()->setOptions($generate)->execute("uptime-0.png");
  $rrd['traffic']->generate()->setOptions($generate)->execute("cpu-0.png");
  $rrd['traffic']->generate()->setOptions($generate)->execute("disk-0.png");
}


/* Other DB

Uptime
rrdtool create uptime.rrd
--start N --step 60
DS:uptime1:GAUGE:600:0:90
DS:uptime5:GAUGE:600:0:90
DS:uptime15:GAUGE:600:0:90
RRA:MIN:0.5:12:1440
RRA:MAX:0.5:12:1440
RRA:AVERAGE:0.5:1:1440

Memory
rrdtool create mem.rrd \
--start N
--step 60
DS:mem_total:GAUGE:150:0:U
DS:mem_free:GAUGE:150:0:U
RRA:AVERAGE:0.5:1:1440
RRA:AVERAGE:0.5:10:1008
RRA:AVERAGE:0.5:60:744

CPU 
rrdtool create cpu.rrd
--start N
--step 60
DS:cpu_user:COUNTER:150:0:100
DS:cpu_nice:COUNTER:150:0:100
DS:cpu_system:COUNTER:150:0:100
DS:cpu_idle:COUNTER:150:0:100
DS:cpu_iowait:COUNTER:150:0:100
DS:cpu_irq:COUNTER:150:0:100
DS:cpu_softirq:COUNTER:150:0:100
RRA:AVERAGE:0.5:1:1440
RRA:AVERAGE:0.5:10:1008
RRA:AVERAGE:0.5:60:744

*/