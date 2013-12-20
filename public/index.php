<?php

require '../vendor/autoload.php';

$servername = isset($_GET['srv']) ?  $_GET['srv'] : null;

if (is_null($servername)) {
    throw new InvalidArgumentException("not specified server name");
}

$configurator = new \wConf\NginxVhost();
$config = $configurator->createConfig($servername);

echo "<pre>$config</pre>";