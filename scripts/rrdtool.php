<?php 

use Asker\Asker;
use Asker\Adapter;
use Rrdtool\Rrdtool;
use Rrdtool\RrdtoolExtension;
use Controller\Helper\Krypt;

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
  $rrd['traffic'] = new Rrdtool($server['ip'], 'traffic.rrd'); // TODO, multi interfaces
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
    $krypt = new Krypt();
    if ($asker = Asker::factory($server['protocol'], $server['ip'], $krypt->decrypt($server['params']))) {
      $rrd['traffic']->update()->setDatas($asker->getTraffic())->execute();
      $rrd['memory']->update()->setDatas($asker->getMemory())->execute();
      $rrd['uptime']->update()->setDatas($asker->getUptime())->execute();
      $rrd['cpu']->update()->setDatas($asker->getCpu())->execute();
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
    "CDEF:inbits=inoctets,8,*",
    "GPRINT:inbits:AVERAGE:Avg\:%6.2lf %Sbps",
    "COMMENT:  ",
    "GPRINT:inbits:MIN:Min\:%6.2lf %Sbps",
    "COMMENT:  ",
    "GPRINT:inbits:MAX:Max\:%6.2lf %Sbps\\r",

    "LINE1:outoctets#0000FF:Out traffic",
    "CDEF:outbits=outoctets,8,*",
    "GPRINT:outbits:AVERAGE:Avg\:%6.2lf %Sbps",
    "COMMENT:  ",
    "GPRINT:outbits:MIN:Min\:%6.2lf %Sbps",
    "COMMENT:  ",
    "GPRINT:outbits:MAX:Max\:%6.2lf %Sbps\\r",

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
    "CDEF:mem_total_resize=mem_total,1024,*",
    "AREA:mem_used#00FF00:Ram Used",
    "CDEF:swap_used=swap_total,swap_free,-,1024,*",
    "CDEF:swap_total_resize=swap_total,1024,*",
    "STACK:swap_used#FF0000:Swap Used",
    "LINE1:mem_total_resize#000000:Ram Limit\\r",

    "COMMENT:\\n",
    "GPRINT:mem_used:AVERAGE:Avg Ram used\: %6.2lf %So",
    "COMMENT:  ",
    "GPRINT:mem_used:MAX:Max Ram used\: %6.2lf %So\\r",
    "GPRINT:swap_used:AVERAGE:Avg Swap used\: %6.2lf %So",
    "COMMENT: ",
    "GPRINT:swap_used:MAX:Max Swap used\: %6.2lf %So\\r",

    "GPRINT:mem_total_resize:LAST:Ram size\: %6.2lf %So",
    "COMMENT: ",
    "GPRINT:swap_total_resize:LAST:Swap size\: %6.2lf %So\\r",
  );
  $rrd['memory']->generate()->setOptions($options)->execute("memory-0.png");

  $options = array("--start", "-1d", "--title", "Load averages of ".$server['servername']." (average of 5min)", "--vertical-label=uptime", "--width", "500", "--height", "200", "-l", "0",
    "DEF:uptime1=".$rrd['uptime']->getDbPath().":uptime1:AVERAGE",
    "DEF:uptime5=".$rrd['uptime']->getDbPath().":uptime5:AVERAGE",
    "DEF:uptime15=".$rrd['uptime']->getDbPath().":uptime15:AVERAGE",

    "AREA:uptime1#ffe000:1min",
    "GPRINT:uptime1:AVERAGE:Avg\: %6.2lf %S",
    "COMMENT:  ",
    "GPRINT:uptime1:MIN:Min\: %6.2lf %S",
    "COMMENT:  ",
    "GPRINT:uptime1:MAX:Max\: %6.2lf %S\\r",

    "AREA:uptime5#ffa000:5min",
    "GPRINT:uptime5:AVERAGE:Avg\: %6.2lf %S",
    "COMMENT:  ",
    "GPRINT:uptime5:MIN:Min\: %6.2lf %S",
    "COMMENT:  ",
    "GPRINT:uptime5:MAX:Max\: %6.2lf %S\\r",

    "AREA:uptime15#ff3333:15min",
    "GPRINT:uptime15:AVERAGE:Avg\: %6.2lf %S",
    "COMMENT:  ",
    "GPRINT:uptime15:MIN:Min\: %6.2lf %S",
    "COMMENT:  ",
    "GPRINT:uptime15:MAX:Max\: %6.2lf %S\\r",
  );

  $rrd['uptime']->generate()->setOptions($options)->execute("uptime-0.png");

  $options = array("--start", "-1d", "--title", "CPU of ".$server['servername']." (average of 5min)", "--vertical-label=%", "--width", "500", "--height", "200", "-l", "0",
    #"--lower-limit", "100",
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
}

$app['monolog']->addInfo(basename(__FILE__) . " script execute in " . (microtime(true) - APPLICATION_MICROTIME_START) . " secondes");
