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

# Ask the server to collect datas

# Add new informations to RDDTOOL DBs

# Generates RRDTOOL Graphs