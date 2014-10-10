<?php

require_once("cronwatcher.php");
$watcher = new cronwatcherClient(1, 1003);
$identifier = $watcher->start();
echo "<br /> Started, returned identifier of " . $identifier;

$sleeptime = (int)rand(4,10);
echo "<br /> sleeping for ".$sleeptime;
sleep($sleeptime);

echo "<br />stopping";
$watcher->stop();
echo "<br />done.";