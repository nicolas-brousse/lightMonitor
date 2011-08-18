<?php 

use Asker\Asker;
use Asker\Adapter;
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
foreach ($app['db']->fetchAll("SELECT * FROM servers") as $server)
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
  $options = array("--start", "N", "--step", "60",
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
  $rrd['traffic']->setup()->setOptions($options)->execute();

  $options = array("--start", "N", "--step", "60",
    "DS:mem_total:GAUGE:600:0:U",
    "DS:mem_free:GAUGE:600:0:U",
    "DS:swap_total:GAUGE:600:0:U",
    "DS:swap_free:GAUGE:600:0:U",
    "RRA:AVERAGE:0.5:1:1440",
    "RRA:AVERAGE:0.5:10:1008",
    "RRA:AVERAGE:0.5:60:744",
  );
  $rrd['memory']->setup()->setOptions($options)->execute();

  $options = array("--start", "N", "--step", "60",
    "DS:uptime1:GAUGE:600:0:90",
    "DS:uptime5:GAUGE:600:0:90",
    "DS:uptime15:GAUGE:600:0:90",
    "RRA:MIN:0.5:12:1440",
    "RRA:MAX:0.5:12:1440",
    "RRA:AVERAGE:0.5:1:1440",
  );
  $rrd['uptime']->setup()->setOptions($options)->execute();

  $options = array("--start", "N", "--step", "60",
    "DS:cpu_user:COUNTER:600:0:100",
    "DS:cpu_nice:COUNTER:600:0:100",
    "DS:cpu_system:COUNTER:600:0:100",
    "DS:cpu_idle:COUNTER:600:0:100",
    "DS:cpu_iowait:COUNTER:600:0:100",
    "DS:cpu_irq:COUNTER:600:0:100",
    "DS:cpu_softirq:COUNTER:600:0:100",
    "RRA:AVERAGE:0.5:1:1440",
    "RRA:AVERAGE:0.5:10:1008",
    "RRA:AVERAGE:0.5:60:744",
  );
  $rrd['cpu']->setup()->setOptions($options)->execute();


  /**
   * Get Informations AND Add new informations to RDDTOOL DBs
   */

  try {
    $configs = array(
      "host" => $server['ip'],
      "port" => $server['port'],
      "login" => $server['login'],
      "pass" => $server['pass'],
    );
    if ($asker = Asker::factory($server['protocol'], $configs)) {
      $rrd['traffic']->update()->setDatas($asker->getTraffic())->execute();
      $rrd['memory']->update()->setDatas($asker->getMemory())->execute();
      $rrd['uptime']->update()->setDatas($asker->getUptime())->execute();
      $rrd['cpu']->update()->setDatas($asker->getCpu())->execute();

      // TODO create graphs of packets
      $app['monolog']->addDebug(var_export($asker->getMemory(), true));
      $app['monolog']->addDebug(var_export($asker->getCpu(), true));
    }
  }
  catch (Exception $e) {
    $app['monolog']->addWarning($e->getMessage());
    if (APPLICATION_ENV == 'development') {
      print($e->getMessage() . PHP_EOL);
    }
  }

  /**
   * Generates RRDTOOL Graphs
   */
  $options = array("--start", "-1d", "--title", "Eth0 Traffic of ".$server['servername']." (average of 5min)", "--vertical-label=B/s", "--width", "500", "--height", "200",
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
  $rrd['traffic']->generate()->setOptions($options)->execute("traffic-0.png");

  $options = array("--start", "-1d", "--title", "Memory of ".$server['servername']." (average of 5min)", "--vertical-label=octets", "--width", "500", "--height", "200",
    "--base", "1024",
    #"--upper-limit", "2e+09",
    "--lower-limit", "0", "-r",
    "DEF:mem_total=".$rrd['memory']->getDbPath().":mem_total:AVERAGE",
    "DEF:mem_free=".$rrd['memory']->getDbPath().":mem_free:AVERAGE",
    "DEF:swap_total=".$rrd['memory']->getDbPath().":swap_total:AVERAGE",
    "DEF:swap_free=".$rrd['memory']->getDbPath().":swap_free:AVERAGE",
    "CDEF:mem_used=mem_total,mem_free,-,1024,*",
    #"CDEF:mem_total_limit=mem_total",
    "CDEF:mem_total_resize=mem_total,1024,*",
    "CDEF:swap_used_resize=swap_free,1024,*",
    "AREA:mem_used#00FF00:RAM Used",
    "LINE1:mem_total_resize#FF0000:RAM Limit",
    "LINE1:swap_used_resize#666000:Swap Used\\r",
    "GPRINT:swap_used_resize:LAST:Max memory %6.2lf %So",
    "GPRINT:mem_total_resize:LAST:Max Swap %6.2lf %So\\r",
  );
  $rrd['memory']->generate()->setOptions($options)->execute("memory-0.png");

  $options = array("--start", "-1d", "--title", "Load averages of ".$server['servername']." (average of 5min)", "--vertical-label=uptime", "--width", "500", "--height", "200", "-l", "0",
    "DEF:uptime1=".$rrd['uptime']->getDbPath().":uptime1:AVERAGE",
    "DEF:uptime5=".$rrd['uptime']->getDbPath().":uptime5:AVERAGE",
    "DEF:uptime15=".$rrd['uptime']->getDbPath().":uptime15:AVERAGE",
    "AREA:uptime1#ffe000:uptime (1min)",
    "AREA:uptime5#ffa000:uptime (5min)",
    "AREA:uptime15#ff3333:uptime (15min)\\r",
  );
  $rrd['uptime']->generate()->setOptions($options)->execute("uptime-0.png");

  $options = array("--start", "-1d", "--title", "CPU of ".$server['servername']." (average of 5min)", "--vertical-label=%", "--width", "500", "--height", "200", "-l", "0",
    "DEF:cpu_user=".$rrd['cpu']->getDbPath().":cpu_user:AVERAGE",
    "DEF:cpu_nice=".$rrd['cpu']->getDbPath().":cpu_nice:AVERAGE",
    "DEF:cpu_system=".$rrd['cpu']->getDbPath().":cpu_system:AVERAGE",
    "DEF:cpu_idle=".$rrd['cpu']->getDbPath().":cpu_idle:AVERAGE",
    "DEF:cpu_iowait=".$rrd['cpu']->getDbPath().":cpu_iowait:AVERAGE",
    "DEF:cpu_irq=".$rrd['cpu']->getDbPath().":cpu_irq:AVERAGE",
    "DEF:cpu_softirq=".$rrd['cpu']->getDbPath().":cpu_softirq:AVERAGE",
    "AREA:cpu_iowait#0000FF:IO wait",
    "STACK:cpu_system#FF9999:System",
    "STACK:cpu_nice#FF99FF:Nice",
    "STACK:cpu_user#99FF99:User",
    "STACK:cpu_idle#FFFFFF:Idle\\r",
  );
  $rrd['cpu']->generate()->setOptions($options)->execute("cpu-0.png");
  //$rrd['traffic']->generate()->setOptions($options)->execute("disk-0.png");
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