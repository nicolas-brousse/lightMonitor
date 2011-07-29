<?php 

use Asker\Asker;
use Asker\Adaptater;
use Rrdtool\Rrdtool;
use Rrdtool\RrdtoolExtension;

/* Launch */
require_once __DIR__.'/bootstrap.php';
$app->register(new RrdtoolExtension());


/**
 *
 * RRDTOOL Graphs generator
 *
 * @author Nicolas BROUSSE <pro@nicolas-brousse.fr>
 **/

# view : http://www.ioncannon.net/system-administration/59/php-rrdtool-tutorial/


# List the servers
foreach ($app['db']->fetchAll("SELECT ip, servername FROM servers") as $server)
{

  /**
   * Intanciate
   */
  $rrd['traffic'] = new Rrdtool($server['ip'], 'traffic.rrd');
  $rrd['memory']  = new Rrdtool($server['ip'], 'memory.rrd');
  $rrd['uptime']  = new Rrdtool($server['ip'], 'uptime.rrd');
  $rrd['cpu']     = new Rrdtool($server['ip'], 'cpu.rrd');


  /**
   * Setup rrd DB
   */
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
  $rrd['traffic']->setup()->setOptions($setup)->execute();

  $setup = array("--start", "N", "--step", "60",
    "DS:mem_total:GAUGE:150:0:U",
    "DS:mem_free:GAUGE:150:0:U",
    "RRA:AVERAGE:0.5:1:1440",
    "RRA:AVERAGE:0.5:10:1008",
    "RRA:AVERAGE:0.5:60:744",
  );
  $rrd['memory']->setup()->setOptions($setup)->execute();

  $setup = array("--start", "N", "--step", "60",
    "DS:uptime1:GAUGE:600:0:90",
    "DS:uptime5:GAUGE:600:0:90",
    "DS:uptime15:GAUGE:600:0:90",
    "RRA:MIN:0.5:12:1440",
    "RRA:MAX:0.5:12:1440",
    "RRA:AVERAGE:0.5:1:1440",
  );
  $rrd['uptime']->setup()->setOptions($setup)->execute();

  $setup = array("--start", "N", "--step", "60",
    "DS:cpu_user:COUNTER:150:0:100",
    "DS:cpu_nice:COUNTER:150:0:100",
    "DS:cpu_system:COUNTER:150:0:100",
    "DS:cpu_idle:COUNTER:150:0:100",
    "DS:cpu_iowait:COUNTER:150:0:100",
    "DS:cpu_irq:COUNTER:150:0:100",
    "DS:cpu_softirq:COUNTER:150:0:100",
    "RRA:AVERAGE:0.5:1:1440",
    "RRA:AVERAGE:0.5:10:1008",
    "RRA:AVERAGE:0.5:60:744",
  );
  $rrd['cpu']->setup()->setOptions($setup)->execute();


  /**
   * Get Informations
   */

  # Ask the server to collect datas
  # $asker = new Asker(Adaptater::SSH);
  #$asker = new Asker(Adaptater::SSH);
  #          $asker = new Asker::getInstance(Adaptater::SSH);
  #var_dump(Adaptater::SSH)
  #var_dump(get_class_methods($asker));
  #var_dump(get_class($asker)); exit;
  #$asker->setHost("ip", "port")->setAuth("root", "pass");
  #var_dump($asker->getUptime()); exit;
  # $asker->getUptime();

  /**
   * Add new informations to RDDTOOL DBs
   */
  $rrd['traffic']->update()->setDatas(array(rand(10000, 15000), rand(10000, 15000)))->execute();
  $rrd['memory']->update()->setDatas(array(rand(10000000, 15000000), rand(10000000, 15000000)))->execute();
  $rrd['uptime']->update()->setDatas(array(rand(0, 2), rand(0, 2), rand(0, 2)))->execute();


  /**
   * Generates RRDTOOL Graphs
   */
  $generate = array("--start", "-1d", "--title", "Traffic of ".$server['servername']." (average of 5min)", "--vertical-label=B/s", "--width", "500", "--height", "200",
    "DEF:inoctets=".$rrd['traffic']->getDbPath().":input:AVERAGE",
    "DEF:outoctets=".$rrd['traffic']->getDbPath().":output:AVERAGE",
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

  $generate = array("--start", "-1d", "--title", "Memory of ".$server['servername']." (average of 5min)", "--vertical-label=octets", "--width", "500", "--height", "200",
    "--base", "1024",
    "--upper-limit", "2e+09", "--lower-limit", "0", "-r",
    "DEF:mem_total=".$rrd['memory']->getDbPath().":mem_total:AVERAGE",
    "DEF:mem_free=".$rrd['memory']->getDbPath().":mem_free:AVERAGE",
    "CDEF:mem_used=mem_total,mem_free,-,1024,*",
    "AREA:mem_used#00FF00:Used",
    "HRULE:2e+09#FF0000:Limit \: 2Go",
    
  );
  $rrd['memory']->generate()->setOptions($generate)->execute("memory-0.png");

  $generate = array("--start", "-1d", "--title", "Load averages of ".$server['servername']." (average of 5min)", "--vertical-label=uptime", "--width", "500", "--height", "200", "-l", "0",
    "DEF:uptime1=".$rrd['uptime']->getDbPath().":uptime1:AVERAGE",
    "DEF:uptime5=".$rrd['uptime']->getDbPath().":uptime5:AVERAGE",
    "DEF:uptime15=".$rrd['uptime']->getDbPath().":uptime15:AVERAGE",
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