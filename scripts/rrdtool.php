<?php 

require_once __DIR__.'/bootstrap.php';

$app->register(new Rrdtool\RrdtoolExtension());

/**
 *
 * Generate RRDTOOL graphics
 *
 **/

# view : http://www.ioncannon.net/system-administration/59/php-rrdtool-tutorial/


# List the servers

# Check if RRDTOOL Data Bases exits, else generate it

use Asker\Asker;
use Asker\Adaptater;
use Rrdtool\Rrdtool;

$rrd['traffic'] = new Rrdtool('127.0.0.1');
$setup = array("–step", "300", "-start", "N",
  "DS:input:COUNTER:600:U:U",
  "DS:output:COUNTER:600:U:U",
  "RRA:AVERAGE:0.5:1:600",
  "RRA:AVERAGE:0.5:6:700",
  "RRA:AVERAGE:0.5:24:775",
  "RRA:AVERAGE:0.5:288:797",
  "RRA:MAX:0.5:1:600",
  "RRA:MAX:0.5:6:700",
  "RRA:MAX:0.5:24:775",
  "RRA:MAX:0.5:288:797"
);
$rrd['traffic']->setup()->setOptions($setup)->execute("traffic.rrd");

$rrd['traffic']->update()->setDatas(array(rand(10000, 15000), rand(10000, 15000)))->execute("traffic.rrd");

$generate = array( "–start", "-1d", "–vertical-label=B/s",
                 "DEF:inoctets=net.rrd:input:AVERAGE",
                 "DEF:outoctets=net.rrd:output:AVERAGE",
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
$rrd['traffic']->generate()->setOptions($generate)->execute("traffic.rrd", "traffic-0.png");

#$asker = new Asker(Adaptater::HTTP);
# $asker = new Asker(Adaptater::HTTP)->setHost($host, $port);
# $asker->getUptime();

# Ask the server to collect datas

# Add new informations to RDDTOOL DBs

# CleanDir(md5($servername)) and Generates RRDTOOL Graphs