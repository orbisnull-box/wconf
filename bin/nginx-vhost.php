<?php

$servername = isset($argv[1]) ? : null;

if (is_null($servername)) {
    throw new InvalidArgumentException("not specified server name");
}

$configurator = new \wConf\NginxVhost();
$configurator->createConfig($servername);